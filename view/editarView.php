<?php

session_start();

if (!isset($_SESSION['usuario_id']) || ($_SESSION['usuario_tipo'] ?? '') !== 'funcionario') {
    header('Location: ../view/loginView.php');
    exit;
}

require_once '../model/carroModel.php';

$carroModel = new Carro();

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: carroView.php");
    exit;
}

$carro = $carroModel->buscarPorId($id);

if (!$carro) {
    echo "Veículo não encontrado.";
    exit;
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Editar <?= htmlspecialchars($carro['modelo']); ?> - DriveGo
    </title>

    <link rel="stylesheet" href="../css/estilo.css">

</head>

<body>

<header class="site-header">

    <div class="container header-inner">

        <a href="../index.php" class="brand-logo">
            Drive<span>Go</span>
        </a>

        <nav class="main-nav">

            <a href="carroView.php" class="nav-link">
                Veículos
            </a>

            <a href="../index.php" class="nav-link">
                Voltar
            </a>

        </nav>

    </div>

</header>


<main class="form-page">

    <div class="form-card">

        <div class="form-header">

            <span class="eyebrow">
                Administração
            </span>

            <h1>
                Editar veículo
            </h1>

            <p>
                Altere as informações do veículo abaixo.
            </p>

        </div>


        <form
            action="../controller/carroController.php"
            method="POST"
            enctype="multipart/form-data"
        >
            <input
                type="hidden"
                name="id"
                value="<?= htmlspecialchars($carro['idVeiculo']); ?>"
            >

            <div class="field">

                <label for="marca">
                    Marca
                </label>

                <input
                    type="text"
                    id="marca"
                    value="<?= htmlspecialchars($carro['nomeMarca']); ?>"
                    class="form-control"
                    readonly
                >

                <input
                    type="hidden"
                    name="idMarca"
                    value="<?= htmlspecialchars($carro['idMarcaV']); ?>"
                >

            </div>

            <div class="field">

                <label for="modelo">
                    Modelo
                </label>

                <input
                    type="text"
                    id="modelo"
                    name="modelo"
                    value="<?= htmlspecialchars($carro['modelo']); ?>"
                    class="form-control"
                    required
                >

            </div>

            <div class="field-row">

                <div class="field">

                    <label for="ano">
                        Ano
                    </label>

                    <input
                        type="text"
                        id="ano"
                        name="ano"
                        value="<?= htmlspecialchars($carro['ano']); ?>"
                        class="form-control"
                        maxlength="4"
                        required
                    >

                </div>

                <div class="field">

                    <label for="valorDiaria">
                        Valor da diária
                    </label>

                    <input
                        type="number"
                        id="valorDiaria"
                        name="valorDiaria"
                        value="<?= htmlspecialchars($carro['valorDiaria']); ?>"
                        class="form-control"
                        step="0.01"
                        min="0"
                        required
                    >

                </div>

            </div>

            <div class="field">

                <label for="statusVeiculo">
                    Estado do veículo
                </label>

                <select id="statusVeiculo" name="statusVeiculo" class="form-control" required>
                    <option value="ativo" <?= $carro['statusVeiculo'] === 'ativo' ? 'selected' : ''; ?>>Ativo — disponível para locação</option>
                    <option value="reservado" <?= $carro['statusVeiculo'] === 'reservado' ? 'selected' : ''; ?>>Reservado</option>
                    <option value="inativo" <?= $carro['statusVeiculo'] === 'inativo' ? 'selected' : ''; ?>>Inativo — temporariamente indisponível</option>
                    <option value="cancelado" <?= $carro['statusVeiculo'] === 'cancelado' ? 'selected' : ''; ?>>Cancelado — fora de operação</option>
                </select>

                <small>Este estado será mostrado ao cliente na página de detalhes.</small>

            </div>

            <div class="field">

                <label for="renavam">
                    RENAVAM
                </label>

                <input
                    type="text"
                    name="renavam"
                    value="<?= htmlspecialchars($carro['renavam']); ?>"
                    class="form-control"
                    readonly
                >

            </div>

            <div class="field">

                <label for="imagemVeiculo">
                    Substituir imagem do veículo
                </label>

                <input
                    type="file"
                    id="imagemVeiculo"
                    name="imagemVeiculo"
                    class="form-control"
                    accept="image/png, image/jpeg, image/webp"
                >

                <small>Deixe em branco para manter a imagem atual.</small>

            </div>

            <div class="field">

                <label>
                    Imagem atual
                </label>

                <img
                    src="../<?= htmlspecialchars($carro['imagemVeiculo']); ?>"
                    alt="<?= htmlspecialchars($carro['modelo']); ?>"
                    class="vehicle-preview"
                >

            </div>

            <div class="form-submit">

                <button
                    type="submit"
                    name="btnEditar"
                    class="btn btn-primary btn-full"
                >
                    Salvar alterações
                </button>

            </div>
                <div class="form-submit">

                <button
                    type="submit"
                    name="btnExcluir"
                    class="btn btn-terciary btn-full"
                >
                    Excluir veículo
                </button>

            </div>


            <div class="form-secondary-action">

                <a href="../index.php">
                    Cancelar
                </a>

            </div>

        </form>

    </div>

</main>

</body>
</html>
