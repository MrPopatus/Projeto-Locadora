<?php
  session_start();

  require_once '../model/carroModel.php';
  require_once '../model/manutencaoModel.php';

  $carroModel = new Carro();

  $carros = $carroModel->listarCarros();

  $manutencaoModel = new Manutencao();
  $numeroAgendamentos = $manutencaoModel->contarAgendadas();
  $manutencoes = $manutencaoModel->listarManutencoes();
  $numeroEmAndamento = $manutencaoModel->contarEmAndamento();
  $numeroFinalizadas = $manutencaoModel->contarFinalizada();
  $custoMensal = $manutencaoModel->contarCusto();
?>


<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Registrar Manutenção - DriveGo</title>

    <link rel="stylesheet" href="../css/estilo.css">

</head>

<body>

<header class="site-header">

    <div class="container header-inner">

        <a href="../index.php" class="brand-logo">
            Drive<span>Go</span>
        </a>

        <nav class="main-nav">

            <a href="../index.php" class="nav-link">
                Voltar
            </a>

        </nav>

    </div>

</header>

<main class="maintenance-page">
    <div class="container">
        <header class="maintenance-header">
            <div>
                <span class="eyebrow">Painel administrativo</span>
                <h1>Gerenciar manutenções</h1>
                <p>Controle os serviços da frota, acompanhe custos e mantenha os veículos disponíveis para locação.</p>
            </div>

            <button type="button" class="btn btn-primary" id="abrir-agendamento" aria-expanded="false" aria-controls="agendamento">
                + Agendar manutenção
            </button>
        </header>

        <section class="maintenance-schedule" id="agendamento" hidden>
            <div class="maintenance-schedule__header">
                <div>
                    <span class="eyebrow">Novo agendamento</span>
                    <h2>Registrar manutenção</h2>
                </div>

                <button type="button" class="maintenance-close" id="fechar-agendamento" aria-label="Fechar formulário">×</button>
            </div>

            <form action="../controller/manutencaoController.php" method="POST">
                <div class="maintenance-form-grid">
                    <div class="field">
                    <label for="veiculo">Veículo:</label>
                    <select id="veiculo" name="veiculo" class="form-control" required>
                        <option value="">Selecione um veículo</option>
                        <?php if (!empty($carros)): ?>
                            <?php foreach ($carros as $carro): ?>
                                <option value="<?= $carro['idVeiculo'] ?>">
                                    <?= htmlspecialchars($carro['modelo']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="" disabled>Nenhuma veículo cadastrado</option>
                        <?php endif; ?>
                    </select>
                </div>

                    <div class="field">
                        <label for="tipo">Tipo de manutenção</label>
                        <select id="tipo" name="tipo" class="form-control" required>
                            <option value="">Selecione o serviço</option>
                            <option value="revisao">Revisão</option>
                            <option value="troca_oleo">Troca de óleo</option>
                            <option value="pneus">Pneus</option>
                            <option value="freios">Freios</option>
                            <option value="corretiva">Manutenção corretiva</option>
                            <option value="outro">Outro</option>
                        </select>
                    </div>

                    <div class="field maintenance-form-grid__full">
                        <label for="descricao">Descrição do serviço</label>
                        <textarea id="descricao" name="descricao" class="form-control" rows="4" placeholder="Descreva o serviço que será realizado."></textarea>
                    </div>

                    <div class="field">
                        <label for="dataInicio">Data de início</label>
                        <input type="date" id="dataInicio" name="dataInicio" class="form-control" required>
                    </div>

                    <div class="field">
                        <label for="dataFim">Previsão de término</label>
                        <input type="date" id="dataFim" name="dataFim" class="form-control" required>
                    </div>

                    <div class="field">
                        <label for="custo">Custo estimado (R$)</label>
                        <input type="number" id="custo" name="custo" class="form-control" min="0" step="0.01" placeholder="Ex: 350.00">
                    </div>

                    <div class="field">
                        <label for="status">Status inicial</label>
                        <select id="status" name="status" class="form-control" required>
                            <option value="agendada">Agendada</option>
                            <option value="em_andamento">Em andamento</option>
                        </select>
                    </div>
                </div>

                <div class="maintenance-form-actions">
                    <button type="button" class="btn btn-outline" id="cancelar-agendamento">Cancelar</button>
                    <button type="submit" class="btn btn-primary" name="btnAgendar">Salvar agendamento</button>
                </div>
            </form>
        </section>

        <section class="maintenance-stats" aria-label="Resumo das manutenções">
            <article class="maintenance-stat">
                <span>Agendadas</span>
                <strong><?= $numeroAgendamentos['total']; ?></strong>
                <small>Serviços previstos</small>
            </article>
            <article class="maintenance-stat maintenance-stat--attention">
                <span>Em andamento</span>
                <strong><?= $numeroEmAndamento['total']; ?></strong>
                <small>Veículos indisponíveis</small>
            </article>
            <article class="maintenance-stat">
                <span>Finalizadas no mês</span>
                <strong><?= $numeroFinalizadas['total']; ?></strong>
                <small>Serviços concluídos</small>
            </article>
            <article class="maintenance-stat">
                <span>Custo mensal</span>
                <strong>R$ <?= number_format($custoMensal['total'], 2, ',', '.'); ?></strong>
                <small>Manutenções finalizadas</small>
            </article>
        </section>

        <section class="maintenance-list">
            <header class="maintenance-list__header">
                <div>
                    <h2>Manutenções registradas</h2>
                    <p>Acompanhe o andamento e o histórico dos serviços da frota.</p>
                </div>

                <div class="maintenance-filters">
                    <select class="form-control" aria-label="Filtrar por status">
                        <option value="">Todos os status</option>
                        <option value="agendada">Agendadas</option>
                        <option value="em_andamento">Em andamento</option>
                        <option value="finalizada">Finalizadas</option>
                    </select>
                    <input type="search" class="form-control" placeholder="Buscar veículo" aria-label="Buscar veículo">
                </div>
            </header>

            <?php if (empty($manutencoes)): ?>
                <div class="maintenance-empty-state">
                    <h3>Nenhuma manutenção registrada</h3>
                    <p>Quando você agendar um serviço, ele aparecerá aqui para acompanhamento.</p>
                    <button type="button" class="btn btn-outline" data-abrir-agendamento>
                        Agendar a primeira manutenção
                    </button>
                </div>
            <?php else: ?>
                <div class="maintenance-records">
                    <?php foreach ($manutencoes as $manutencao): ?>
                        <article class="maintenance-record">
                            <div class="maintenance-record__main">
                                <div>
                                    <h3><?= htmlspecialchars($manutencao['nomeMarca'] . ' ' . $manutencao['modelo']); ?></h3>
                                    <p><?= htmlspecialchars($manutencao['tipo']); ?></p>
                                </div>
                                <span class="maintenance-status maintenance-status--<?= htmlspecialchars($manutencao['status']); ?>">
                                    <?= htmlspecialchars(str_replace('_', ' ', $manutencao['status'])); ?>
                                </span>
                            </div>

                            <p class="maintenance-record__description">
                                <?= htmlspecialchars($manutencao['descricao'] ?: 'Sem descrição informada.'); ?>
                            </p>

                            <div class="maintenance-record__details">
                                <span>Início: <strong><?= date('d/m/Y', strtotime($manutencao['dataInicio'])); ?></strong></span>
                                <span>Previsão: <strong><?= $manutencao['dataFim'] ? date('d/m/Y', strtotime($manutencao['dataFim'])) : 'Não informada'; ?></strong></span>
                                <span>Custo: <strong>R$ <?= number_format((float) $manutencao['custo'], 2, ',', '.'); ?></strong></span>
                            </div>

                            <div class="maintenance-record__actions">
                                <button
                                    type="button"
                                    class="btn btn-outline btn-status"
                                    data-abrir-status="status-modal-<?= (int) $manutencao['idManutencao']; ?>"
                                >
                                    Atualizar status
                                </button>
                            </div>
                        </article>

                        <div
                            class="maintenance-modal"
                            id="status-modal-<?= (int) $manutencao['idManutencao']; ?>"
                            role="dialog"
                            aria-modal="true"
                            aria-labelledby="status-modal-titulo-<?= (int) $manutencao['idManutencao']; ?>"
                            hidden
                        >
                            <div class="maintenance-modal__backdrop" data-fechar-status></div>
                            <div class="maintenance-modal__content">
                                <div class="maintenance-modal__header">
                                    <div>
                                        <span class="eyebrow">Agendamento registrado</span>
                                        <h2 id="status-modal-titulo-<?= (int) $manutencao['idManutencao']; ?>">Atualizar status</h2>
                                    </div>
                                    <button type="button" class="maintenance-close" data-fechar-status aria-label="Fechar modal">×</button>
                                </div>

                                <p class="maintenance-modal__vehicle">
                                    <?= htmlspecialchars($manutencao['nomeMarca'] . ' ' . $manutencao['modelo']); ?>
                                </p>

                                <form action="../controller/manutencaoController.php" method="POST">
                                    <input type="hidden" name="idManutencao" value="<?= (int) $manutencao['idManutencao']; ?>">
                                    <div class="field">
                                        <label for="status-<?= (int) $manutencao['idManutencao']; ?>">Status do agendamento</label>
                                        <select id="status-<?= (int) $manutencao['idManutencao']; ?>" name="status" class="form-control" required>
                                            <option value="agendada" <?= $manutencao['status'] === 'agendada' ? 'selected' : ''; ?>>Agendada</option>
                                            <option value="em_andamento" <?= $manutencao['status'] === 'em_andamento' ? 'selected' : ''; ?>>Em andamento</option>
                                            <option value="finalizada" <?= $manutencao['status'] === 'finalizada' ? 'selected' : ''; ?>>Finalizada</option>
                                        </select>
                                    </div>

                                    <div class="maintenance-form-actions">
                                        <button type="button" class="btn btn-outline" data-fechar-status>Cancelar</button>
                                        <button type="submit" class="btn btn-primary" name="btnAtualizarStatus">Salvar status</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </div>
</main>

<script>
    const painelAgendamento = document.getElementById('agendamento');
    const botaoAbrirAgendamento = document.getElementById('abrir-agendamento');
    const botaoFecharAgendamento = document.getElementById('fechar-agendamento');
    const botaoCancelarAgendamento = document.getElementById('cancelar-agendamento');
    const botoesAbrirAgendamento = document.querySelectorAll('[data-abrir-agendamento]');

    function alternarAgendamento(abrir) {
        painelAgendamento.hidden = !abrir;
        botaoAbrirAgendamento.setAttribute('aria-expanded', String(abrir));

        if (abrir) {
            painelAgendamento.scrollIntoView({ behavior: 'smooth', block: 'start' });
            document.getElementById('veiculo').focus();
        }
    }

    botaoAbrirAgendamento.addEventListener('click', () => alternarAgendamento(true));
    botaoFecharAgendamento.addEventListener('click', () => alternarAgendamento(false));
    botaoCancelarAgendamento.addEventListener('click', () => alternarAgendamento(false));
    botoesAbrirAgendamento.forEach((botao) => {
        botao.addEventListener('click', () => alternarAgendamento(true));
    });

    const botoesAbrirStatus = document.querySelectorAll('[data-abrir-status]');
    const botoesFecharStatus = document.querySelectorAll('[data-fechar-status]');

    botoesAbrirStatus.forEach((botao) => {
        botao.addEventListener('click', () => {
            const modal = document.getElementById(botao.dataset.abrirStatus);
            modal.hidden = false;
            modal.querySelector('select').focus();
        });
    });

    botoesFecharStatus.forEach((botao) => {
        botao.addEventListener('click', () => {
            botao.closest('.maintenance-modal').hidden = true;
        });
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            document.querySelectorAll('.maintenance-modal:not([hidden])').forEach((modal) => {
                modal.hidden = true;
            });
        }
    });
</script>

</body>
</html>
