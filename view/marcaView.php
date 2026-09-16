<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Marca</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>
     <main class="form-page">

        <section class="form-card">

            <header class="form-header">
                <h1>Cadastrar Marcas</h1>
            </header>

            <form action="../controller/marcaController.php" method="POST">

                <div class="field">
                    <label for="marca">nome da marca</label>
                    <input
                        type="text"
                        id="marca"
                        name="marca"
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
                        Cadastrar
                    </button>
                </div>

            </form>

            <div class="form-secondary-action">
                <a href="../index.php"> 
                    Voltar ao Início
                </a>
            </div>

        </section>

    </main>
</body>
</html>