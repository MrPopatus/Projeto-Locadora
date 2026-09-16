<?php
require_once '../model/marcaModel.php';
if (isset($_POST['btnCadastrar'])) {
    $nomeMarca = $_POST['marca'];

    $marcaModel = new Marca();
    $sucesso = $marcaModel->inserir($nomeMarca);

    if ($sucesso) {
        echo "<script>
                    alert('Cadastro realizado com sucesso!');
                    window.location.href = '../view/marcaView.php';
                </script>";

        // Redireciona via PHP com parâmetro de mensagem


    } else {
        echo "<script>
                    alert('Erro ao realizar o cadastro. Tente novamente.');
                    window.location.href = '../view/marcaView.php';
                </script>";
    }
}
