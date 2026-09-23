<?php
session_start();
if (!isset($_SESSION['usuario_id']) || ($_SESSION['usuario_tipo'] ?? '') !== 'funcionario') {
    header('Location: ../loginView.php');
    exit;
}

require_once __DIR__ . '/../../model/dashboardModel.php';
$dashboard = new Dashboard();
$resumo = $dashboard->resumo();
$frota = $dashboard->statusFrota();
$devolucoes = $dashboard->devolucoesHoje();
$manutencoes = $dashboard->manutencoesProximas();
$reservas = $dashboard->reservasRecentes();
$lucro = (float) $resumo['receitaMes'] - (float) $resumo['custoMes'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - DriveGo</title>
    <link rel="stylesheet" href="../../css/estilo.css">
</head>
<body>
<header class="site-header">
    <div class="container header-inner">
        <a href="../../index.php" class="brand-logo">Drive<span>Go</span></a>
        <nav class="main-nav">
            <a href="../../index.php" class="nav-link">Frota</a>
            <a href="manutencaoView.php" class="nav-link">Manutenções</a>
            <a href="clienteView.php" class="nav-link">Clientes</a>
        </nav>
    </div>
</header>

<main class="maintenance-page">
    <div class="container">
        <header class="maintenance-header">
            <div>
                <span class="eyebrow">Painel administrativo</span>
                <h1>Visão geral da operação</h1>
                <p>Acompanhe a frota, a receita do mês e os itens que exigem ação da equipe.</p>
            </div>
            <div class="maintenance-form-actions">
                <a href="carroView.php" class="btn btn-outline">Cadastrar veículo</a>
                <a href="manutencaoView.php" class="btn btn-primary">Agendar manutenção</a>
            </div>
        </header>

        <section class="maintenance-stats" aria-label="Indicadores principais">
            <article class="maintenance-stat"><span>Veículos cadastrados</span><strong><?= (int) $resumo['totalVeiculos'] ?></strong><small>Frota total registrada</small></article>
            <article class="maintenance-stat"><span>Veículos disponíveis</span><strong><?= (int) $resumo['veiculosDisponiveis'] ?></strong><small>Prontos para nova locação</small></article>
            <article class="maintenance-stat maintenance-stat--attention"><span>Locações em andamento</span><strong><?= (int) $resumo['locacoesAtivas'] ?></strong><small>Reservas e contratos ativos</small></article>
            <article class="maintenance-stat"><span>Receita do mês</span><strong>R$ <?= number_format((float) $resumo['receitaMes'], 2, ',', '.') ?></strong><small>Locações iniciadas neste mês</small></article>
            <article class="maintenance-stat"><span>Lucro estimado do mês</span><strong>R$ <?= number_format($lucro, 2, ',', '.') ?></strong><small>Receita menos manutenções finalizadas</small></article>
        </section>

        <section class="maintenance-list" aria-label="Status da frota">
            <header class="maintenance-list__header"><div><h2>Status da frota</h2><p>Distribuição atual dos veículos por estado operacional.</p></div></header>
            <div class="maintenance-records">
                <article class="maintenance-record"><div class="maintenance-record__main"><h3>Disponíveis</h3><span class="maintenance-status maintenance-status--finalizada"><?= $frota['ativo'] ?></span></div><p class="maintenance-record__description">Veículos prontos para novas locações.</p></article>
                <article class="maintenance-record"><div class="maintenance-record__main"><h3>Reservados</h3><span class="maintenance-status maintenance-status--agendada"><?= $frota['reservado'] ?></span></div><p class="maintenance-record__description">Veículos vinculados a reservas ou contratos ativos.</p></article>
                <article class="maintenance-record"><div class="maintenance-record__main"><h3>Indisponíveis</h3><span class="maintenance-status maintenance-status--em_andamento"><?= $frota['inativo'] + $frota['cancelado'] ?></span></div><p class="maintenance-record__description">Veículos inativos ou fora de operação.</p></article>
            </div>
        </section>

        <section class="maintenance-list" style="margin-top: 1.5rem;" aria-label="Alertas operacionais">
            <header class="maintenance-list__header"><div><h2>Alertas e acompanhamento</h2><p>Devoluções previstas, manutenções próximas e novas reservas registradas.</p></div></header>
            <div class="maintenance-records">
                <?php if ($devolucoes): ?>
                    <?php foreach ($devolucoes as $item): ?>
                        <article class="maintenance-record"><div class="maintenance-record__main"><div><h3>Devolução prevista para hoje</h3><p>Cliente: <?= htmlspecialchars($item['nomeCliente']) ?></p></div><span class="maintenance-status maintenance-status--agendada">Hoje</span></div><p class="maintenance-record__description">Veículo: <?= htmlspecialchars($item['nomeMarca'] . ' ' . $item['modelo']) ?> · Verifique a devolução e a situação do veículo.</p></article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <article class="maintenance-record"><div class="maintenance-record__main"><div><h3>Nenhuma devolução prevista para hoje</h3><p>A agenda está em dia.</p></div><span class="maintenance-status maintenance-status--finalizada">OK</span></div></article>
                <?php endif; ?>

                <?php foreach ($manutencoes as $item): ?>
                    <article class="maintenance-record"><div class="maintenance-record__main"><div><h3>Manutenção <?= htmlspecialchars(str_replace('_', ' ', $item['status'])) ?></h3><p>Veículo: <?= htmlspecialchars($item['nomeMarca'] . ' ' . $item['modelo']) ?></p></div><span class="maintenance-status maintenance-status--em_andamento"><?= date('d/m', strtotime($item['dataInicio'])) ?></span></div><p class="maintenance-record__description">Serviço: <?= htmlspecialchars($item['tipo']) ?>.</p></article>
                <?php endforeach; ?>

                <?php foreach ($reservas as $item): ?>
                    <article class="maintenance-record"><div class="maintenance-record__main"><div><h3>Reserva registrada</h3><p>Cliente: <?= htmlspecialchars($item['nomeCliente']) ?></p></div><span class="maintenance-status maintenance-status--finalizada"><?= date('d/m', strtotime($item['dataLocacao'])) ?></span></div><p class="maintenance-record__description">Veículo: <?= htmlspecialchars($item['nomeMarca'] . ' ' . $item['modelo']) ?> · Status: <?= htmlspecialchars(str_replace('_', ' ', $item['statusLocacao'])) ?>.</p><?php if (in_array($item['statusLocacao'], ['reservado', 'ativo'], true)): ?><form action="../../controller/finalizarLocacaoController.php" method="post"><input type="hidden" name="idLocacao" value="<?= (int) $item['idLocacao'] ?>"><button type="submit" class="btn btn-outline btn-small">Registrar devolução</button></form><?php endif; ?></article>
                <?php endforeach; ?>
            </div>
        </section>
    </div>
</main>
</body>
</html>
