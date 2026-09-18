<?php
session_start();

if (!isset($_SESSION['usuario_id']) || ($_SESSION['usuario_tipo'] ?? '') !== 'funcionario') {
    header('Location: ../view/loginView.php');
    exit;
}

require_once '../model/cadastroModel.php';

$clienteModel = new Cliente();
$resumo = $clienteModel->buscarResumo();
$clientes = $clienteModel->listarClientes();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes - DriveGo</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>
<header class="site-header">
    <div class="container header-inner">
        <a href="../index.php" class="brand-logo">Drive<span>Go</span></a>
        <nav class="main-nav"><a href="../index.php" class="nav-link">Voltar</a></nav>
    </div>
</header>

<main class="clients-page">
    <div class="container">
        <header class="clients-header">
            <div>
                <span class="eyebrow">Painel administrativo</span>
                <h1>Gerenciar clientes</h1>
                <p>Consulte a base de clientes e acompanhe o histórico de locações em um só lugar.</p>
            </div>
        </header>

        <section class="clients-stats" aria-label="Resumo dos clientes">
            <article class="client-stat"><span>Total de clientes</span><strong><?= (int) $resumo['totalClientes']; ?></strong><small>Cadastros ativos na plataforma</small></article>
            <article class="client-stat client-stat--attention"><span>Com locação ativa</span><strong><?= (int) $resumo['clientesAtivos']; ?></strong><small>Reservas ou contratos em andamento</small></article>
            <article class="client-stat"><span>Com histórico</span><strong><?= (int) $resumo['clientesComHistorico']; ?></strong><small>Clientes que já realizaram locação</small></article>
            <article class="client-stat"><span>Receita acumulada</span><strong>R$ <?= number_format((float) $resumo['receitaTotal'], 2, ',', '.'); ?></strong><small>Valor total das locações registradas</small></article>
        </section>

        <section class="clients-list">
            <header class="clients-list__header">
                <div><h2>Clientes cadastrados</h2><p>Abra um perfil para consultar os dados e o histórico de locações.</p></div>
                <div class="clients-filters">
                    <select class="form-control" id="filtro-situacao" aria-label="Filtrar por situação">
                        <option value="">Todas as situações</option>
                        <option value="ativo">Com locação ativa</option>
                        <option value="sem-locacao">Sem locação ativa</option>
                    </select>
                    <input type="search" class="form-control" id="buscar-cliente" placeholder="Buscar nome, e-mail ou telefone" aria-label="Buscar cliente">
                </div>
            </header>

            <?php if (empty($clientes)): ?>
                <div class="clients-empty-state"><h3>Nenhum cliente cadastrado</h3><p>Os clientes aparecerão aqui assim que concluírem o cadastro na plataforma.</p></div>
            <?php else: ?>
                <div class="clients-records" id="lista-clientes">
                    <?php foreach ($clientes as $cliente): ?>
                        <?php
                        $historico = $clienteModel->listarHistoricoLocacoes((int) $cliente['idCliente']);
                        $temLocacaoAtiva = (int) $cliente['locacoesAtivas'] > 0;
                        ?>
                        <article class="client-record" data-cliente="<?= htmlspecialchars(strtolower($cliente['nomeCliente'] . ' ' . $cliente['email'] . ' ' . $cliente['telefone']), ENT_QUOTES, 'UTF-8'); ?>" data-situacao="<?= $temLocacaoAtiva ? 'ativo' : 'sem-locacao'; ?>">
                            <div class="client-record__identity">
                                <div class="client-avatar" aria-hidden="true"><?= htmlspecialchars(strtoupper(mb_substr($cliente['nomeCliente'], 0, 1))); ?></div>
                                <div><h3><?= htmlspecialchars($cliente['nomeCliente']); ?></h3><p><?= htmlspecialchars($cliente['email']); ?></p></div>
                            </div>
                            <div class="client-record__meta">
                                <span>Telefone: <strong><?= htmlspecialchars($cliente['telefone']); ?></strong></span>
                                <span>Locações: <strong><?= (int) $cliente['totalLocacoes']; ?></strong></span>
                                <span>Última locação: <strong><?= $cliente['ultimaLocacao'] ? date('d/m/Y', strtotime($cliente['ultimaLocacao'])) : 'Nenhuma'; ?></strong></span>
                            </div>
                            <div class="client-record__footer">
                                <span class="client-status <?= $temLocacaoAtiva ? 'client-status--active' : 'client-status--inactive'; ?>"><?= $temLocacaoAtiva ? 'Com locação ativa' : 'Sem locação ativa'; ?></span>
                                <button type="button" class="btn btn-outline btn-client-profile" data-abrir-cliente="cliente-modal-<?= (int) $cliente['idCliente']; ?>">Ver cliente</button>
                            </div>
                        </article>

                        <div class="client-modal" id="cliente-modal-<?= (int) $cliente['idCliente']; ?>" role="dialog" aria-modal="true" aria-labelledby="cliente-modal-titulo-<?= (int) $cliente['idCliente']; ?>" hidden>
                            <div class="client-modal__backdrop" data-fechar-cliente></div>
                            <div class="client-modal__content">
                                <div class="client-modal__header">
                                    <div><span class="eyebrow">Perfil do cliente</span><h2 id="cliente-modal-titulo-<?= (int) $cliente['idCliente']; ?>"><?= htmlspecialchars($cliente['nomeCliente']); ?></h2></div>
                                    <button type="button" class="maintenance-close" data-fechar-cliente aria-label="Fechar modal">×</button>
                                </div>
                                <dl class="client-profile-data">
                                    <div><dt>E-mail</dt><dd><?= htmlspecialchars($cliente['email']); ?></dd></div>
                                    <div><dt>Telefone</dt><dd><?= htmlspecialchars($cliente['telefone']); ?></dd></div>
                                    <div><dt>Locações registradas</dt><dd><?= (int) $cliente['totalLocacoes']; ?></dd></div>
                                </dl>
                                <section class="client-history" aria-label="Histórico de locações">
                                    <h3>Histórico de locações</h3>
                                    <?php if (empty($historico)): ?>
                                        <p class="client-history__empty">Este cliente ainda não possui locações registradas.</p>
                                    <?php else: ?>
                                        <div class="client-history__list">
                                            <?php foreach ($historico as $locacao): ?>
                                                <article class="client-history__item">
                                                    <div><strong><?= htmlspecialchars($locacao['nomeMarca'] . ' ' . $locacao['modelo']); ?></strong><span><?= date('d/m/Y', strtotime($locacao['dataLocacao'])); ?> até <?= date('d/m/Y', strtotime($locacao['dataDevolucaoPrevista'])); ?></span></div>
                                                    <div class="client-history__summary"><span class="rental-status rental-status--<?= htmlspecialchars($locacao['statusLocacao']); ?>"><?= htmlspecialchars(str_replace('_', ' ', $locacao['statusLocacao'])); ?></span><strong>R$ <?= number_format((float) $locacao['valorPagar'], 2, ',', '.'); ?></strong></div>
                                                </article>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </section>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <p class="clients-no-results" id="clientes-sem-resultados" hidden>Nenhum cliente encontrado com esses filtros.</p>
            <?php endif; ?>
        </section>
    </div>
</main>

<script>
    const campoBusca = document.getElementById('buscar-cliente');
    const filtroSituacao = document.getElementById('filtro-situacao');
    const registrosClientes = document.querySelectorAll('.client-record');
    const mensagemSemResultados = document.getElementById('clientes-sem-resultados');

    function filtrarClientes() {
        const busca = campoBusca.value.trim().toLowerCase();
        const situacao = filtroSituacao.value;
        let totalVisivel = 0;
        registrosClientes.forEach((registro) => {
            const visivel = registro.dataset.cliente.includes(busca) && (!situacao || registro.dataset.situacao === situacao);
            registro.hidden = !visivel;
            totalVisivel += visivel ? 1 : 0;
        });
        mensagemSemResultados.hidden = totalVisivel !== 0;
    }

    campoBusca?.addEventListener('input', filtrarClientes);
    filtroSituacao?.addEventListener('change', filtrarClientes);
    document.querySelectorAll('[data-abrir-cliente]').forEach((botao) => botao.addEventListener('click', () => {
        const modal = document.getElementById(botao.dataset.abrirCliente);
        modal.hidden = false;
        modal.querySelector('[data-fechar-cliente]').focus();
    }));
    document.querySelectorAll('[data-fechar-cliente]').forEach((botao) => botao.addEventListener('click', () => {
        botao.closest('.client-modal').hidden = true;
    }));
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') document.querySelectorAll('.client-modal:not([hidden])').forEach((modal) => modal.hidden = true);
    });
</script>
</body>
</html>
