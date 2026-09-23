<?php

require_once '../model/loginModel.php';

session_start();

if (isset($_POST['btnLogar'])) {

    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $loginModel = new Login();

    $usuario = $loginModel->buscarPorEmail($email);

    if (
        !$usuario ||
        !password_verify($senha, $usuario['senha'])
    ) {
        echo "<script>
                alert('E-mail ou senha incorretos.');
                window.location.href = '../view/loginView.php';
            </script>";
        exit;
    }

    session_regenerate_id(true);

    $_SESSION['usuario_id'] = $usuario['idCliente'];
    $_SESSION['usuario_nome'] = $usuario['nomeCliente'];
    $_SESSION['usuario_tipo'] = $usuario['tipoUsuario'];

    header("Location: ../index.php");

    exit;
}