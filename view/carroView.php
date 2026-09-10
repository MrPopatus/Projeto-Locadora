<!DOCTYPE html>
<html lang="pt-bt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar veículo</title>
</head>
<body>
     <main class="form-page">

        <section class="form-card">

            <header class="form-header">
                <h1>Cadastrar Veículo</h1>
            </header>

            <form action="../controller/carroController.php" method="POST">

                 <label for="marca">Marca:</label>

                <select id="marca" name="marca" class="form-control" required>

                    <option value="">
                        Selecione uma marca
                    </option>

                    <?php foreach ($marcas as $marca): ?>

                        <option value="<?= $marca['idMarca'] ?>">
                            <?= htmlspecialchars($marca['nomeMarca']) ?>
                        </option>

                    <?php endforeach; ?>

                </select>


                <div class="field">
                    <label for="modelo">modelo do veículo</label>
                    <input
                        type="text"
                        id="modelo"
                        name="modelo"
                        class="form-control"
                        required
                    >
                </div>

                <div class="field">
                    <label for="senha">ano do veículo</label>
                    <input
                        type="year"
                        id="ano"
                        name="ano"
                        class="form-control"
                        required
                    >
                </div>

                <div class="field">
                    <label for="senha">RENAVAM</label>
                    <input
                        type="text"
                        id="renavam"
                        name="renavam"
                        class="form-control"
                        required
                    >
                </div>

                <div class="field">
                    <label for="senha">Valor da díaria</label>
                    <input
                        type="decimal"
                        id="valor"
                        name="valor"
                        class="form-control"
                        required
                    >
                </div>

                <div class="form-submit">
                    <button
                        type="submit"
                        name="btnCadastrar"
                        class="btn btn-primary btn-full"
                    >
                        Entrar
                    </button>
                </div>

            </form>

            <div class="form-secondary-action">
                <a href="../index.php">
                    ← Voltar ao Início
                </a>
            </div>

        </section>

    </main>
</body>
</html>