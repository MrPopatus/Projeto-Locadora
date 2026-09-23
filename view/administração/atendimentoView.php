<?php
session_start();

if (!isset($_SESSION['usuario_id']) || ($_SESSION['usuario_tipo'] ?? '') !== 'funcionario') {
    header('Location: ../loginView.php');
    exit;
}

require_once __DIR__ . '/../../model/atendimentoModel.php';
$atendimentoModel = new Atendimento();
$atendimentos = $atendimentoModel->listarAtendimentos();
$idAtendimentoSelecionado = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$idAtendimentoSelecionado && !empty($atendimentos)) {
    $idAtendimentoSelecionado = (int) $atendimentos[0]['idAtendimento'];
}

$atendimentoSelecionado = $idAtendimentoSelecionado
    ? $atendimentoModel->buscarPorId($idAtendimentoSelecionado)
    : false;
$mensagensAtendimento = $atendimentoSelecionado
    ? $atendimentoModel->listarMensagens($idAtendimentoSelecionado)
    : [];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Atendimento | DriveGo</title>
    <link rel="stylesheet" href="../../css/estilo.css?v=20260922-2">
</head>
<body
    class="support-page support-page--admin"
    data-tipo-usuario="funcionario"
    data-usuario-id="<?= (int) $_SESSION['usuario_id'] ?>"
    data-atendimento-id="<?= $atendimentoSelecionado ? (int) $idAtendimentoSelecionado : '' ?>"
>
<header class="support-topbar">
    <div class="container support-topbar__inner">
        <a href="../../index.php" class="brand-logo">Drive<span>Go</span></a>
        <div class="support-topbar__actions">
            <span class="support-operator">Operador: <?= htmlspecialchars($_SESSION['usuario_nome'] ?? 'Funcionário') ?></span>
            <a href="dashboardView.php" class="btn btn-outline support-back-button" aria-label="Retornar ao painel administrativo">
                <span aria-hidden="true">←</span>
                Retornar
            </a>
        </div>
    </div>
</header>

<main class="support-main">
    <div class="container support-container support-container--admin">
        <header class="support-page-header">
            <div>
                <span class="eyebrow">Painel administrativo</span>
                <h1>Central de Atendimento</h1>
                <p>Acompanhe as solicitações dos clientes e responda cada conversa em tempo real.</p>
            </div>
            <div id="status-websocket" class="support-connection" data-conectado="false">
                <span class="support-connection__dot" aria-hidden="true"></span>
                <span>Conectando…</span>
            </div>
        </header>

        <div class="support-workspace">
            <aside class="support-sidebar">
                <header class="support-sidebar__header">
                    <div>
                        <span class="eyebrow">Conversas</span>
                        <h2>Atendimentos</h2>
                    </div>
                    <span class="support-sidebar__count"><?= count($atendimentos) ?></span>
                </header>

                <nav class="atendimentos support-conversations" aria-label="Lista de atendimentos">
                    <?php if (empty($atendimentos)): ?>
                        <div class="lista-vazia support-conversations__empty">
                            <span aria-hidden="true">•••</span>
                            <strong>Nenhum atendimento</strong>
                            <p>As novas conversas aparecerão aqui.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($atendimentos as $item): ?>
                            <?php
                                $nomeCliente = (string) $item['nomeCliente'];
                                $iniciais = strtoupper(substr($nomeCliente, 0, 1));
                                $ativo = (int) $item['idAtendimento'] === (int) $idAtendimentoSelecionado;
                            ?>
                            <a
                                href="?id=<?= (int) $item['idAtendimento'] ?>"
                                data-atendimento-id="<?= (int) $item['idAtendimento'] ?>"
                                class="support-conversation <?= $ativo ? 'ativo' : '' ?>"
                            >
                                <span class="support-conversation__avatar" aria-hidden="true"><?= htmlspecialchars($iniciais) ?></span>
                                <span class="support-conversation__content">
                                    <strong><?= htmlspecialchars($nomeCliente) ?></strong>
                                    <small>Atendimento #<?= (int) $item['idAtendimento'] ?></small>
                                </span>
                                <span class="support-conversation__status support-conversation__status--<?= htmlspecialchars($item['statusAtendimento']) ?>">
                                    <?= htmlspecialchars($item['statusAtendimento']) ?>
                                </span>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </nav>
            </aside>

            <section class="central support-chat support-chat--admin" aria-label="Conversa selecionada">
                <header class="support-chat__header">
                    <?php if ($atendimentoSelecionado): ?>
                        <?php $inicialSelecionada = strtoupper(substr((string) $atendimentoSelecionado['nomeCliente'], 0, 1)); ?>
                        <div class="support-agent-avatar support-agent-avatar--client" aria-hidden="true"><?= htmlspecialchars($inicialSelecionada) ?></div>
                        <div>
                            <h2><?= htmlspecialchars($atendimentoSelecionado['nomeCliente']) ?></h2>
                            <p>Atendimento #<?= (int) $idAtendimentoSelecionado ?> · <?= htmlspecialchars($atendimentoSelecionado['statusAtendimento']) ?></p>
                        </div>
                    <?php else: ?>
                        <div class="support-agent-avatar support-agent-avatar--client" aria-hidden="true">?</div>
                        <div>
                            <h2>Nenhuma conversa selecionada</h2>
                            <p>Aguarde um cliente iniciar um atendimento.</p>
                        </div>
                    <?php endif; ?>
                </header>

                <div class="mensagens support-messages" id="mensagens" aria-live="polite">
                    <?php if (!$atendimentoSelecionado): ?>
                        <div class="support-empty-chat mensagem-vazia">
                            <span aria-hidden="true">✦</span>
                            <h3>Você está em dia</h3>
                            <p>Quando uma nova solicitação chegar, ela aparecerá automaticamente nesta tela.</p>
                        </div>
                    <?php elseif (empty($mensagensAtendimento)): ?>
                        <div class="support-empty-chat mensagem-vazia">
                            <span aria-hidden="true">✦</span>
                            <h3>Conversa iniciada</h3>
                            <p>Este atendimento ainda não possui mensagens.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($mensagensAtendimento as $mensagem): ?>
                            <article class="mensagem <?= htmlspecialchars($mensagem['tipoRemetente']) ?>">
                                <div class="mensagem__bubble">
                                    <div class="mensagem__meta">
                                        <strong><?= htmlspecialchars($mensagem['nomeRemetente']) ?></strong>
                                        <span><?= date('H:i', strtotime($mensagem['dataEnvio'])) ?></span>
                                    </div>
                                    <p><?= nl2br(htmlspecialchars($mensagem['conteudo'])) ?></p>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <?php if ($atendimentoSelecionado && $atendimentoSelecionado['statusAtendimento'] === 'aberto'): ?>
                    <form id="formulario-atendimento" class="formulario support-composer" action="../../controller/atendimentoController.php" method="POST">
                        <input type="hidden" name="idAtendimento" value="<?= (int) $idAtendimentoSelecionado ?>">
                        <label class="sr-only" for="mensagem">Digite sua resposta</label>
                        <input
                            type="text"
                            id="mensagem"
                            name="mensagem"
                            placeholder="Digite sua resposta..."
                            autocomplete="off"
                            maxlength="2000"
                            required
                        >
                        <button type="submit" name="btnEnviarMensagem" class="btn btn-primary support-send-button">
                            Enviar
                            <span aria-hidden="true">→</span>
                        </button>
                    </form>

                    <form id="formulario-encerrar" class="support-close-form" action="../../controller/atendimentoController.php" method="POST">
                        <input type="hidden" name="idAtendimento" value="<?= (int) $idAtendimentoSelecionado ?>">
                        <button type="submit" name="btnEncerrarAtendimento" class="support-close-button">Encerrar atendimento</button>
                    </form>
                <?php elseif ($atendimentoSelecionado): ?>
                    <div class="support-closed-notice">Este atendimento foi encerrado.</div>
                <?php endif; ?>
            </section>
        </div>
    </div>
</main>

<script src="../../js/atendimento.js?v=20260922-2"></script>
</body>
</html>
