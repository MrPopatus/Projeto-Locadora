<?php

use Workerman\Connection\TcpConnection;
use Workerman\Protocols\Http\Request;
use Workerman\Worker;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../model/atendimentoModel.php';

$arquivoTrava = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'drivego-websocket.lock';
$travaServidor = fopen($arquivoTrava, 'c');

if (!$travaServidor || !flock($travaServidor, LOCK_EX | LOCK_NB)) {
    fwrite(STDERR, "O servidor WebSocket da DriveGo já está em execução.\n");
    exit(1);
}

$servidor = new Worker('websocket://0.0.0.0:8080');
$servidor->name = 'DriveGo Atendimento';
$servidor->count = 1;
Worker::$logFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'drivego-workerman.log';

function enviarJson(TcpConnection $conexao, array $dados): void
{
    $conexao->send(json_encode($dados, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
}

function carregarUsuarioDaSessao(?string $idSessao): ?array
{
    if (!$idSessao || !preg_match('/^[a-zA-Z0-9,-]{1,128}$/', $idSessao)) {
        return null;
    }

    $diretorioSessoes = session_save_path() ?: sys_get_temp_dir();
    $arquivoSessao = rtrim($diretorioSessoes, '/\\') . DIRECTORY_SEPARATOR . 'sess_' . $idSessao;

    if (!is_file($arquivoSessao)) {
        return null;
    }

    if (session_status() === PHP_SESSION_ACTIVE) {
        session_write_close();
    }

    session_id($idSessao);

    if (!@session_start()) {
        return null;
    }

    $usuario = isset($_SESSION['usuario_id'])
        ? [
            'id' => (int) $_SESSION['usuario_id'],
            'nome' => (string) ($_SESSION['usuario_nome'] ?? 'Usuário'),
            'tipo' => (string) ($_SESSION['usuario_tipo'] ?? 'cliente')
        ]
        : null;

    session_write_close();
    return $usuario;
}

function transmitirAtendimento(Worker $servidor, array $evento, array $atendimento): void
{
    foreach ($servidor->connections as $conexao) {
        if (!($conexao->context->autenticado ?? false)) {
            continue;
        }

        $ehFuncionario = ($conexao->context->tipoUsuario ?? '') === 'funcionario';
        $ehClienteDono = (int) ($conexao->context->usuarioId ?? 0) === (int) $atendimento['idClienteA'];

        if ($ehFuncionario || $ehClienteDono) {
            enviarJson($conexao, $evento);
        }
    }
}

$servidor->onWebSocketConnect = function (TcpConnection $conexao, Request $request): void {
    $origem = (string) $request->header('origin', '');
    $hostPermitido = parse_url($origem, PHP_URL_HOST);

    if (!in_array($hostPermitido, ['localhost', '127.0.0.1'], true)) {
        $conexao->context->autenticado = false;
        return;
    }

    $usuario = carregarUsuarioDaSessao($request->cookie(session_name()));

    if (!$usuario || !in_array($usuario['tipo'], ['cliente', 'funcionario'], true)) {
        $conexao->context->autenticado = false;
        return;
    }

    $conexao->context->autenticado = true;
    $conexao->context->usuarioId = $usuario['id'];
    $conexao->context->usuarioNome = $usuario['nome'];
    $conexao->context->tipoUsuario = $usuario['tipo'];
};

$servidor->onWebSocketConnected = function (TcpConnection $conexao): void {
    if (!($conexao->context->autenticado ?? false)) {
        enviarJson($conexao, ['tipo' => 'erro', 'mensagem' => 'Sessão inválida. Entre novamente.']);
        $conexao->close();
        return;
    }

    enviarJson($conexao, ['tipo' => 'conectado']);
};

$servidor->onMessage = function (TcpConnection $conexao, string $dados) use ($servidor): void {
    if (!($conexao->context->autenticado ?? false)) {
        $conexao->close();
        return;
    }

    $evento = json_decode($dados, true);

    if (!is_array($evento) || !isset($evento['tipo'])) {
        enviarJson($conexao, ['tipo' => 'erro', 'mensagem' => 'Mensagem inválida.']);
        return;
    }

    $model = new Atendimento();
    $idUsuario = (int) $conexao->context->usuarioId;
    $tipoUsuario = (string) $conexao->context->tipoUsuario;

    if ($evento['tipo'] === 'enviar_mensagem') {
        $conteudo = trim((string) ($evento['conteudo'] ?? ''));

        if ($conteudo === '' || mb_strlen($conteudo) > 2000) {
            enviarJson($conexao, ['tipo' => 'erro', 'mensagem' => 'A mensagem deve ter entre 1 e 2000 caracteres.']);
            return;
        }

        if ($tipoUsuario === 'cliente') {
            $atendimento = $model->buscarAbertoPorCliente($idUsuario);
            $idAtendimento = $atendimento
                ? (int) $atendimento['idAtendimento']
                : $model->criar($idUsuario);
            $atendimento = $idAtendimento ? $model->buscarPorId($idAtendimento) : false;
        } else {
            $idAtendimento = filter_var($evento['idAtendimento'] ?? null, FILTER_VALIDATE_INT);
            $atendimento = $idAtendimento ? $model->buscarPorId($idAtendimento) : false;

            if ($atendimento) {
                $model->atribuirFuncionario($idAtendimento, $idUsuario);
            }
        }

        if (!$atendimento || $atendimento['statusAtendimento'] !== 'aberto') {
            enviarJson($conexao, ['tipo' => 'erro', 'mensagem' => 'Atendimento inválido ou encerrado.']);
            return;
        }

        if (!$model->inserirMensagem($idAtendimento, $idUsuario, $tipoUsuario, $conteudo)) {
            enviarJson($conexao, ['tipo' => 'erro', 'mensagem' => 'Não foi possível salvar a mensagem.']);
            return;
        }

        transmitirAtendimento($servidor, [
            'tipo' => 'nova_mensagem',
            'idAtendimento' => (int) $idAtendimento,
            'idRemetente' => $idUsuario,
            'tipoRemetente' => $tipoUsuario,
            'nomeRemetente' => (string) $conexao->context->usuarioNome,
            'nomeCliente' => (string) $atendimento['nomeCliente'],
            'conteudo' => $conteudo,
            'dataEnvio' => date('Y-m-d H:i:s')
        ], $atendimento);
        return;
    }

    if ($evento['tipo'] === 'encerrar_atendimento' && $tipoUsuario === 'funcionario') {
        $idAtendimento = filter_var($evento['idAtendimento'] ?? null, FILTER_VALIDATE_INT);
        $atendimento = $idAtendimento ? $model->buscarPorId($idAtendimento) : false;

        if (!$atendimento || !$model->encerrar($idAtendimento, $idUsuario)) {
            enviarJson($conexao, ['tipo' => 'erro', 'mensagem' => 'Não foi possível encerrar o atendimento.']);
            return;
        }

        transmitirAtendimento($servidor, [
            'tipo' => 'atendimento_encerrado',
            'idAtendimento' => (int) $idAtendimento
        ], $atendimento);
        return;
    }

    enviarJson($conexao, ['tipo' => 'erro', 'mensagem' => 'Ação não reconhecida.']);
};

$servidor->onError = function (TcpConnection $conexao, int $codigo, string $mensagem): void {
    echo "Erro {$codigo}: {$mensagem}\n";
    $conexao->close();
};

Worker::runAll();
