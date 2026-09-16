
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fazer Login</title>
     <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>
    <form action="../controller/loginController.php" method="POST">

    <div class="field">

        <label for="email">email</label>

        <input type="email" id="email" name="email" class="form-control" required>

    </div>

    <div class="field">

        <label for="senha">Senha</label>

        <input type="password" id="senha" name="senha" class="form-control" required>
    </div>


    <div class="form-submit">
        <button type="submit" name="btnLogar" class="btn btn-primary btn-full">
            Entrar
        </button>

    </div>

</form>
</body>
</html>