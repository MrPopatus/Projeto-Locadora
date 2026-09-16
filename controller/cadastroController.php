<?php
require_once '../model/cadastroModel.php';
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
                    window.location.href = '../index.php';
                </script>";


    } else {
        echo "<script>
                    alert('Erro ao realizar o cadastro. Tente novamente.');
                    window.location.href = '../view/cadastroView.php';
                </script>";
    }
}
