<?php

require_once __DIR__ . '/../../model/carroModel.php';
require_once __DIR__ . '/../../model/reserva2Model.php';

$carroModel = new Carro();

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: ../../index.php");
    exit;
}

$carro = $carroModel->buscarPorId($id);

if (!$carro) {
    echo "Veículo não encontrado.";
    exit;
}


$reserva2Model = new Reserva();

$statusVeiculo = $carro['statusVeiculo'] ?? 'ativo';
$statusInfo = [
    'ativo' => ['titulo' => 'Disponível para locação', 'mensagem' => 'Este veículo está ativo e pode ser reservado.', 'classe' => 'vehicle-availability--active'],
    'reservado' => ['titulo' => 'Veículo reservado', 'mensagem' => 'Este veículo já está reservado e não pode receber uma nova solicitação agora.', 'classe' => 'vehicle-availability--reserved'],
    'inativo' => ['titulo' => 'Temporariamente indisponível', 'mensagem' => 'Este veículo está inativo e não está disponível para locação no momento.', 'classe' => 'vehicle-availability--inactive'],
    'cancelado' => ['titulo' => 'Veículo fora de operação', 'mensagem' => 'Este veículo foi retirado da operação e não pode ser reservado.', 'classe' => 'vehicle-availability--cancelled'],
];
$avisoStatus = $statusInfo[$statusVeiculo] ?? $statusInfo['inativo'];
$disponivel = $statusVeiculo === 'ativo' && $reserva2Model->estaDisponivel($carro['idVeiculo']);

if ($statusVeiculo === 'ativo' && !$disponivel) {
    $avisoStatus = ['titulo' => 'Veículo reservado', 'mensagem' => 'Este veículo já possui uma reserva ou locação em andamento.', 'classe' => 'vehicle-availability--reserved'];
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?= htmlspecialchars($carro['nomeMarca']); ?>
        <?= htmlspecialchars($carro['modelo']); ?>
        - DriveGo
    </title>

    <link rel="stylesheet" href="../../css/estilo.css">

</head>

<body>

<header class="site-header">

    <div class="container header-inner">

        <a href="../../index.php" class="brand-logo">
            Drive<span>Go</span>
        </a>

        <nav class="main-nav">

            <a href="../../index.php" class="nav-link">
                Voltar
            </a>

        </nav>

    </div>

</header>


<main class="catalog-section">

    <div class="container">

        <div class="section-heading">

            <div>

                <span class="eyebrow">
                    Detalhes do veículo
                </span>

                <h2>
                    <?= htmlspecialchars($carro['nomeMarca']); ?>
                    <?= htmlspecialchars($carro['modelo']); ?>
                </h2>

            </div>

        </div>


        <div class="vehicle-card">

            <div class="vehicle-image-wrapper">

                <img
                    src="<?= htmlspecialchars($carro['imagemVeiculo']); ?>"
                    alt="<?= htmlspecialchars($carro['modelo']); ?>"
                    class="vehicle-image"
                >

            </div>


            <div class="vehicle-content">

                <h1 class="vehicle-title">

                    <?= htmlspecialchars($carro['nomeMarca']); ?>

                    <?= htmlspecialchars($carro['modelo']); ?>

                </h1>
                <p class="vehicle-subtitle">

                    Ano
                    <?= htmlspecialchars($carro['ano']); ?>

                </p>

                <div class="vehicle-availability <?= htmlspecialchars($avisoStatus['classe']); ?>" role="status">
                    <strong><?= htmlspecialchars($avisoStatus['titulo']); ?></strong>
                    <span><?= htmlspecialchars($avisoStatus['mensagem']); ?></span>
                </div>
                <div class="vehicle-specs">

                    <div class="spec-item">

                        Marca:
                        <?= htmlspecialchars($carro['nomeMarca']); ?>

                    </div>

                    <div class="spec-item">

                        Ano:
                        <?= htmlspecialchars($carro['ano']); ?>

                    </div>

                </div>
                <div class="vehicle-footer">

                    <div class="price-box">

                        <span class="price-amount">

                            R$
                            <?= number_format(
                                $carro['valorDiaria'],
                                2,
                                ',',
                                '.'
                            ); ?>

                        </span>

                        <span class="price-period">
                            por dia
                        </span>
                    </div>


                    <?php if ($disponivel): ?>

                        <a
                            href="reservaView.php?carro_id=<?= $carro['idVeiculo']; ?>"
                            class="btn btn-accent"
                        >
                            Reservar veículo
                        </a>

                    <?php else: ?>

                        <button class="btn btn-terciary" disabled>
                            <?= htmlspecialchars($avisoStatus['titulo']); ?>
                        </button>

                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

</body>
</html>
