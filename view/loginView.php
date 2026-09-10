<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>
    
    <div class="container-formulario">
        <h2>Fazer Login</h2>
        
        <form action="../controller/loginController.php" method="POST">
            <div class="grupo-campo">
                <label for="nome">Nome*:</label>
                <input type="text" id="nome" name="nome" required>
            </div>
            
            <div class="grupo-campo">
                <label for="tel">Telefone*:</label>
                <input type="text" id="tel" name="tel" required>
            </div>
            
            <div class="grupo-campo">
                <label for="cpf">CPF*:</label>
                <input type="text" id="cpf" name="cpf" required>
            </div>
            <div class="grupo-campo">
                <label for="email">Email*:</label>
                <input type="text" id="email" name="email" required>
            </div>
            <button type="submit" name="btnLogar">Logar</button>
        </form>
    
        <a href="../index.php" class="botao-voltar-index">Voltar ao Início</a>
    
    </div>
</body>
</html>