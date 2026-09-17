<?php

session_start();

if (!isset($_SESSION['reserva'])) {
    header("Location: ../index.php");
    exit;
}

$reserva = $_SESSION['reserva'];

$valorPagar = $reserva['valorPagar'];

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Confirmar Locação | DriveGo</title>

    <link rel="stylesheet" href="../css/estilo.css">

</head>

<body>

    <main class="form-page">

        <section class="form-card">

            <header class="form-header">

                <span class="eyebrow">
                    Alugue Agora
                </span>

                <h1>
                    Confirmar Locação
                </h1>

                <p>
                    Preencha os dados abaixo para finalizar sua locação.
                </p>

            </header>


            <div class="field">

                <h2>
                    Valor a pagar:
                    R$
                    <?= number_format(
                        $valorPagar,
                        2,
                        ',',
                        '.'
                    ); ?>
                </h2>

            </div>


            <form
                action="../controller/reserva2Controller.php"
                method="POST"
            >

                <div class="field">

                    <label for="cpf">
                        CPF
                    </label>

                    <input
                        type="text"
                        id="cpf"
                        name="cpf"
                        class="form-control"
                        maxlength="11"
                        required
                    >

                </div>


                <div class="field">

                    <label for="cnh">
                        CNH
                    </label>

                    <input
                        type="text"
                        id="cnh"
                        name="cnh"
                        class="form-control"
                        maxlength="9"
                        required
                    >

                </div>


                <div class="field">

                    <label for="numeroCartao">
                        Número do Cartão de Crédito
                    </label>

                    <input
                        type="text"
                        id="numeroCartao"
                        name="numeroCartao"
                        class="form-control"
                        maxlength="16"
                        required
                    >

                </div>


                <div class="form-submit">

                    <button
                        type="submit"
                        name="btnConfirmarLocacao"
                        class="btn btn-primary btn-full"
                    >
                        Confirmar Locação
                    </button>

                </div>

            </form>


            <div class="form-secondary-action">

                <a href="../index.php">
                    Voltar ao início
                </a>

            </div>

        </section>

    </main>

</body>
</html>