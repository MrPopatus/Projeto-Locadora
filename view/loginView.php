
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar | DriveGo</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body class="auth-page">
    <main class="auth-shell">
        <section class="auth-visual">
            <div class="auth-visual__top"><a href="../index.php" class="brand-logo brand-logo--on-dark">Drive<span>Go</span></a></div>
            <div class="auth-visual__body">
                <span class="eyebrow eyebrow--light">Bem-vindo de volta</span>
                <h1>Seu próximo destino começa aqui.</h1>
                <p>Acesse sua conta para reservar veículos e acompanhar suas locações.</p>
            </div>
            <div class="speed-lines"><span></span><span></span><span></span><span></span></div>
        </section>
        <section class="auth-panel">
            <div class="auth-panel__inner">
                <h2>Entrar na conta</h2>
                <p class="auth-panel__lead">Informe seus dados para continuar.</p>
                <form action="../controller/loginController.php" method="POST">
                    <div class="field"><label for="email">E-mail</label><input type="email" id="email" name="email" class="form-control" required></div>
                    <div class="field"><label for="senha">Senha</label><input type="password" id="senha" name="senha" class="form-control" required></div>
                    <div class="form-submit"><button type="submit" name="btnLogar" class="btn btn-primary btn-full">Entrar</button></div>
                </form>
                <p class="auth-panel__footer">Ainda não tem conta? <a href="cadastroView.php">Cadastre-se</a></p>
                <a class="botao-voltar-index" href="../index.php">Voltar ao início</a>
            </div>
        </section>
    </main>
</body>
</html>
