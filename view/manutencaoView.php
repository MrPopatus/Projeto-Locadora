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
                        <label for="veiculo">Veículo</label>
                        <select id="veiculo" name="veiculo" class="form-control" required>
                            <option value="">Selecione um veículo</option>
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
                        <input type="date" id="dataFim" name="dataFim" class="form-control">
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
                    <button type="submit" class="btn btn-primary">Salvar agendamento</button>
                </div>
            </form>
        </section>

        <section class="maintenance-stats" aria-label="Resumo das manutenções">
            <article class="maintenance-stat">
                <span>Agendadas</span>
                <strong>0</strong>
                <small>Serviços previstos</small>
            </article>
            <article class="maintenance-stat maintenance-stat--attention">
                <span>Em andamento</span>
                <strong>0</strong>
                <small>Veículos indisponíveis</small>
            </article>
            <article class="maintenance-stat">
                <span>Finalizadas no mês</span>
                <strong>0</strong>
                <small>Serviços concluídos</small>
            </article>
            <article class="maintenance-stat">
                <span>Custo mensal</span>
                <strong>R$ 0,00</strong>
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

            <div class="maintenance-empty-state">
                <h3>Nenhuma manutenção registrada</h3>
                <p>Quando você agendar um serviço, ele aparecerá aqui para acompanhamento.</p>
                <button type="button" class="btn btn-outline" data-abrir-agendamento>
                    Agendar a primeira manutenção
                </button>
            </div>
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
</script>

</body>
</html>
