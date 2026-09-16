
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login</title>

    <link rel="stylesheet" href="../css/estilo.css">
</head>

<body>

    <main class="form-page">

        <section class="form-card">

            <header class="form-header">
                <h1>Fazer Login</h1>
                <p>Entre na sua conta para continuar.</p>
            </header>

            <form action="../controller/cadastroController.php" method="POST">

                <div class="field">
                    <label for="nome">Nome</label>
                    <input
                        type="text"
                        id="nome"
                        name="nome"
                        class="form-control"
                        required
                    >
                </div>

                <div class="field">
                    <label for="tel">Telefone</label>
                    <input
                        type="text"
                        id="tel"
                        name="tel"
                        class="form-control"
                        required
                    >
                </div>

                <div class="field">
                    <label for="email">E-mail</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control"
                        required
                    >
                </div>

                <div class="field">
                    <label for="senha">Senha</label>
                    <input
                        type="password"
                        id="senha"
                        name="senha"
                        class="form-control"
                        required
                    >
                </div>

                <div class="form-submit">
                    <button
                        type="submit"
                        name="btnLogar"
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
