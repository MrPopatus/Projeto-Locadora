<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../loginView.php');
    exit;
}

if (($_SESSION['usuario_tipo'] ?? '') === 'funcionario') {
    header('Location: ../administração/atendimentoView.php');
    exit;
}

require_once __DIR__ . '/../../model/atendimentoModel.php';
$atendimentoModel = new Atendimento();
$atendimentoAtual = $atendimentoModel->buscarAbertoPorCliente((int) $_SESSION['usuario_id']);
$mensagensAtendimento = $atendimentoAtual
    ? $atendimentoModel->listarMensagens((int) $atendimentoAtual['idAtendimento'])
    : [];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Central de Atendimento | DriveGo</title>
    <link rel="stylesheet" href="../../css/estilo.css?v=20260922-2">
</head>
<body
    class="support-page support-page--client"
    data-tipo-usuario="cliente"
    data-usuario-id="<?= (int) $_SESSION['usuario_id'] ?>"
    data-atendimento-id="<?= $atendimentoAtual ? (int) $atendimentoAtual['idAtendimento'] : '' ?>"
>
<header class="support-topbar">
    <div class="container support-topbar__inner">
        <a href="../../index.php" class="brand-logo">Drive<span>Go</span></a>
        <a href="../../index.php" class="btn btn-outline support-back-button" aria-label="Retornar à página inicial">
            <span aria-hidden="true">←</span>
            Retornar
        </a>
    </div>
</header>

<main class="support-main">
    <div class="container support-container support-container--client">
        <header class="support-page-header">
            <div>
                <span class="eyebrow">Suporte DriveGo</span>
                <h1>Central de Atendimento</h1>
                <p>Fale com nossa equipe sobre reservas, veículos ou qualquer dúvida durante sua jornada.</p>
            </div>
            <div id="status-websocket" class="support-connection" data-conectado="false">
                <span class="support-connection__dot" aria-hidden="true"></span>
                <span>Conectando…</span>
            </div>
        </header>

        <section class="central support-chat" aria-label="Conversa com a equipe de atendimento">
            <header class="support-chat__header">
                <div class="support-agent-avatar" aria-hidden="true">DG</div>
                <div>
                    <h2>Equipe de atendimento</h2>
                    <p>Estamos aqui para ajudar você</p>
                </div>
                <?php if ($atendimentoAtual): ?>
                    <span class="support-ticket">Atendimento #<?= (int) $atendimentoAtual['idAtendimento'] ?></span>
                <?php endif; ?>
            </header>

            <div class="mensagens support-messages" id="mensagens" aria-live="polite">
                <?php if (empty($mensagensAtendimento)): ?>
                    <article class="mensagem funcionario mensagem-vazia">
                        <div class="mensagem__bubble">
                            <div class="mensagem__meta">
                                <strong>Equipe DriveGo</strong>
                                <span>Agora</span>
                            </div>
                            <p>Olá, <?= htmlspecialchars($_SESSION['usuario_nome'] ?? 'cliente') ?>! Como podemos ajudar?</p>
                        </div>
                    </article>
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

            <form id="formulario-atendimento" class="formulario support-composer" action="../../controller/atendimentoController.php" method="POST">
                <label class="sr-only" for="mensagem">Digite sua mensagem</label>
                <input
                    type="text"
                    id="mensagem"
                    name="mensagem"
                    placeholder="Digite sua mensagem..."
                    autocomplete="off"
                    maxlength="2000"
                    required
                >
                <button type="submit" name="btnEnviarMensagem" class="btn btn-primary support-send-button">
                    Enviar
                    <span aria-hidden="true">→</span>
                </button>
            </form>
        </section>

        <p class="support-security-note">
            <span aria-hidden="true">●</span>
            Suas mensagens ficam registradas com segurança neste atendimento.
        </p>
    </div>
</main>

<script src="../../js/atendimento.js?v=20260922-2"></script>
</body>
</html>
