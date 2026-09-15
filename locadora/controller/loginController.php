<?php
require_once '../model/loginModel.php';
if (isset($_POST['btnLogar'])) {
    $nomeCliente = $_POST['nome'];
    $telefone = $_POST['tel'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $clienteModel = new Cliente();
    $sucesso = $clienteModel->inserir($nomeCliente, $telefone, $email, $senha);

    if ($sucesso) {
        echo "<script>
                    alert('Cadastro realizado com sucesso!');
                    window.location.href = '../view/loginView.php';
                </script>";

        // Redireciona via PHP com parâmetro de mensagem


    } else {
        echo "<script>
                    alert('Erro ao realizar o cadastro. Tente novamente.');
                    window.location.href = '../view/loginView.php';
                </script>";
    }
}
