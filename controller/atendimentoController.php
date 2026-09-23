<?php

session_start();
require_once __DIR__ . '/../model/atendimentoModel.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../view/loginView.php');
    exit;
}

if (isset($_POST['btnEncerrarAtendimento'])) {
    if (($_SESSION['usuario_tipo'] ?? '') !== 'funcionario') {
        header('Location: ../index.php');
        exit;
    }

    $idAtendimento = filter_input(INPUT_POST, 'idAtendimento', FILTER_VALIDATE_INT);
    $atendimentoModel = new Atendimento();
    $sucesso = $idAtendimento
        && $atendimentoModel->encerrar($idAtendimento, (int) $_SESSION['usuario_id']);

    $mensagem = $sucesso
        ? 'Atendimento encerrado com sucesso.'
        : 'Não foi possível encerrar o atendimento.';
    $destino = '../view/administração/atendimentoView.php?id=' . (int) $idAtendimento;

    echo "<script>alert(" . json_encode($mensagem) . "); window.location.href = " . json_encode($destino) . ";</script>";
    exit;
}

if (isset($_POST['btnEnviarMensagem'])) {
    $mensagem = trim($_POST['mensagem'] ?? '');
    $tipoUsuario = $_SESSION['usuario_tipo'] ?? 'cliente';
    $idUsuario = (int) $_SESSION['usuario_id'];

    if ($mensagem === '' || mb_strlen($mensagem) > 2000) {
        echo "<script>alert('A mensagem deve ter entre 1 e 2000 caracteres.'); window.history.back();</script>";
        exit;
    }

    $atendimentoModel = new Atendimento();

    if ($tipoUsuario === 'funcionario') {
        $idAtendimento = filter_input(INPUT_POST, 'idAtendimento', FILTER_VALIDATE_INT);
        $atendimento = $idAtendimento ? $atendimentoModel->buscarPorId($idAtendimento) : false;

        if (!$atendimento || $atendimento['statusAtendimento'] !== 'aberto') {
            echo "<script>alert('Atendimento inválido ou encerrado.'); window.history.back();</script>";
            exit;
        }

        $atendimentoModel->atribuirFuncionario($idAtendimento, $idUsuario);
        $sucesso = $atendimentoModel->inserirMensagem($idAtendimento, $idUsuario, 'funcionario', $mensagem);
        $destino = '../view/administração/atendimentoView.php?id=' . $idAtendimento;
    } else {
        $atendimento = $atendimentoModel->buscarAbertoPorCliente($idUsuario);
        $idAtendimento = $atendimento
            ? (int) $atendimento['idAtendimento']
            : $atendimentoModel->criar($idUsuario);

        $sucesso = $idAtendimento
            && $atendimentoModel->inserirMensagem($idAtendimento, $idUsuario, 'cliente', $mensagem);
        $destino = '../view/cliente/atendimentoView.php';
    }

    if ($sucesso) {
        header('Location: ' . $destino);
    } else {
        echo "<script>alert('Não foi possível enviar a mensagem. Tente novamente.'); window.location.href = '" . $destino . "';</script>";
    }
    exit;
}

header('Location: ../index.php');
exit;
