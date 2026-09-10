<?php
    require_once '../model/loginModel.php';
    if(isset($_POST['btnLogar'])){
        $nomeCliente = $_POST['nome'];
        $telefone = $_POST['tel'];
        $cpf = $_POST['cpf'];
        $email = $_POST['email'];

        $clienteModel = new Cliente(); 
        $sucesso = $clienteModel->inserir($nomeCliente, $telefone, $cpf, $email);

        if ($sucesso) {
            echo "<script>
                    alert('Cadastro realizado com sucesso!');
                    window.location.href = '../view/loginView.php';
                </script>";
              
                // Redireciona via PHP com parâmetro de mensagem
                header("Location: ../view/loginView.php?status=sucesso");
                exit();

        } else {
            echo "<script>
                    alert('Erro ao realizar o cadastro. Tente novamente.');
                    window.location.href = '../view/cadastroView.php';
                </script>";
        }

        session_start();
            require_once '../model/clienteModel.php';

            if (isset($_POST['btnLogar'])) {
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nome'] = $usuario['nome'];
                
                header("Location: ../index.php");
                exit();
            }
    }
?>