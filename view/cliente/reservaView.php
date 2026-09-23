<?php

$carroId = $_GET['carro_id'] ?? null;

if (!$carroId) {
    header("Location: ../../index.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Fazer Reserva | DriveGo</title>

    <link rel="stylesheet" href="../../css/estilo.css">
</head>

<body>

    <main class="form-page">

        <section class="form-card">

            <header class="form-header">
                <span class="eyebrow">Alugue Agora</span>
                <h1>Preencha o Formulário de Locação</h1>
            </header>
            <form action="../../controller/reservaController.php" method="POST">

                <input
                    type="hidden"
                    name="idVeiculo"
                    value="<?= htmlspecialchars($carroId); ?>"
                >

                <div class="field">
                    <label for="dataDevolucaoPrevista">
                        Dia de Devolução
                    </label>

                    <input
                        type="date"
                        id="dataDevolucaoPrevista"
                        name="dataDevolucaoPrevista"
                        class="form-control"
                        required
                    >
                </div>

                <div class="form-submit">
                    <button
                        type="submit"
                        name="btnContinuarReserva"
                        class="btn btn-primary btn-full"
                    >
                        Continuar
                    </button>
                </div>

            </form>

            <div class="form-secondary-action">
                <a href="../../index.php">
                     Voltar ao início
                </a>
            </div>

        </section>

    </main>

</body>
</html>
