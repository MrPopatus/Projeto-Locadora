<?php
require_once '../model/cadastroModel.php';
if (isset($_POST['btnLogar'])) {
    $nomeCliente = $_POST['nome'];
    $telefone = $_POST['tel'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    if ($senhaHash === false) {
    echo "<script>
            alert('Não foi possível proteger a senha.');
            window.location.href = '../view/cadastroView.php';
          </script>";
    exit;
}


    $clienteModel = new Cliente();
    $sucesso = $clienteModel->inserir($nomeCliente, $telefone, $email, $senhaHash);

    if ($sucesso) {
        echo "<script>
                    alert('Cadastro realizado com sucesso!');
                    window.location.href = '../view/loginView.php';
                </script>";


    } else {
        echo "<script>
                    alert('Erro ao realizar o cadastro. Tente novamente.');
                    window.location.href = '../view/cadastroView.php';
                </script>";
    }
}
