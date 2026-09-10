<?php

require_once '../model/marcaModel.php';
require_once '../model/carroModel.php';

if (!isset($_POST['btnCadastrar'])) {

    $marcaModel = new Marca();

    $marcas = $marcaModel->listarMarcas();

    require_once '../view/carroView.php';

    exit();
}

if (isset($_POST['btnCadastrar'])) {

    $modelo = $_POST['modelo'];
    $ano = $_POST['ano'];
    $renavam = $_POST['renavam'];
    $valorDiaria = $_POST['valor'];
    $idMarca = $_POST['marca'];


    $carroModel = new Carro();

    $sucesso = $carroModel->inserir($modelo,$ano,$renavam,$valorDiaria,$idMarca);

    if ($sucesso) {

        echo "<script>
                alert('Cadastro realizado com sucesso!');
                window.location.href = '../view/carroView.php';
              </script>";

    } else {

        echo "<script>
                alert('Erro ao realizar o cadastro. Tente novamente.');
                window.location.href = '../view/carroView.php';
              </script>";
    }
}

?>