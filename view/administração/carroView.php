<?php
if (!isset($marcas)) {
    require_once '../../model/marcaModel.php';
    $marcaModel = new Marca();
    $marcas = $marcaModel->listarMarcas();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Veículo</title>
    <link rel="stylesheet" href="../../css/estilo.css">
</head>

<body>
    <main class="form-page">

        <section class="form-card">

            <header class="form-header">
                <span class="eyebrow">Painel administrativo</span>
                <h1>Cadastrar Veículo</h1>
                <p>Inclua um novo modelo para exibi-lo na frota disponível.</p>
            </header>

            <form action="../../controller/carroController.php" method="POST" enctype="multipart/form-data">

                <div class="field">
                    <label for="marca">Marca:</label>
                    <select id="marca" name="marca" class="form-control" required>
                        <option value="">Selecione uma marca</option>
                        <?php if (!empty($marcas)): ?>
                            <?php foreach ($marcas as $marca): ?>
                                <option value="<?= $marca['idMarca'] ?>">
                                    <?= htmlspecialchars($marca['nomeMarca']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="" disabled>Nenhuma marca cadastrada</option>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="field">
                    <label for="modelo">Modelo do veículo</label>
                    <input
                        type="text"
                        id="modelo"
                        name="modelo"
                        class="form-control"
                        placeholder="Ex: Corolla XEi"
                        required>
                </div>

                <div class="field">
                    <label for="ano">Ano do veículo</label>
                    <input
                        type="number"
                        id="ano"
                        name="ano"
                        min="1900"
                        max="2099"
                        class="form-control"
                        placeholder="Ex: 2024"
                        required>
                </div>

                <div class="field">
                    <label for="renavam">RENAVAM</label>
                    <input
                        type="text"
                        id="renavam"
                        name="renavam"
                        class="form-control"
                        placeholder="Ex: 123456789"
                        required>
                </div>

                <div class="field">
                    <label for="valor">Valor da diária (R$)</label>
                    <input
                        type="number"
                        step="0.01"
                        min="0"
                        id="valor"
                        name="valor"
                        class="form-control"
                        placeholder="Ex: 150.00"
                        required>
                </div>
                <div class="field">
                    <label for="imagemVeiculo">Upload de uma imagem do veículo</label>
                    <input
                        type="file"
                        name="imagemVeiculo"
                        id="imagemVeiculo"
                        accept="image/png, image/jpeg, image/webp"
                        required>
                </div>
                <div class="form-submit">
                    <button
                        type="submit"
                        name="btnCadastrar"
                        class="btn btn-primary btn-full">
                        Cadastrar Veículo
                    </button>
                </div>

            </form>

            <div class="form-secondary-action">
                <a href="../../index.php">
                    ← Voltar ao Início
                </a>
            </div>

        </section>

    </main>
</body>

</html>
