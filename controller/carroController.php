<?php

require_once '../model/marcaModel.php';
require_once '../model/carroModel.php';

if (
    !isset($_POST['btnCadastrar']) &&
    !isset($_POST['btnEditar']) &&
    !isset($_POST['btnExcluir'])
) {

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
    $imagemVeiculo = $_POST['imagemVeiculo'];

    $carroModel = new Carro();

    $sucesso = $carroModel->inserir(
        $modelo,
        $ano,
        $renavam,
        $valorDiaria,
        $idMarca,
        $imagemVeiculo
    );

    if ($sucesso) {

        echo "<script>
                alert('Cadastro realizado com sucesso!');
                window.location.href = '../index.php.php';
              </script>";

    } else {

        echo "<script>
                alert('Erro ao realizar o cadastro. Tente novamente.');
                window.location.href = '../index.php';
              </script>";
    }
}


if (isset($_POST['btnEditar'])) {

    $id = $_POST['id'];
    $modelo = $_POST['modelo'];
    $ano = $_POST['ano'];
    $renavam = $_POST['renavam'];
    $valorDiaria = $_POST['valorDiaria'];
    $idMarca = $_POST['idMarca'];
    $imagemVeiculo = $_POST['imagemVeiculo'];

    $carroModel = new Carro();

    $sucesso = $carroModel->editar(
        $id,
        $modelo,
        $ano,
        $renavam,
        $valorDiaria,
        $idMarca,
        $imagemVeiculo
    );

    if ($sucesso) {

        echo "<script>
                alert('Veículo atualizado com sucesso!');
                window.location.href = '../view/editarView.php';
              </script>";

    } else {

        echo "<script>
                alert('Erro ao atualizar o veículo.');
                window.location.href = '../view/editarView.php';
              </script>";
    }
}

if (isset($_POST['btnExcluir'])) {

    $id = $_POST['id'];

    $carroModel = new Carro();

    $sucesso = $carroModel->excluir($id);

    if ($sucesso) {

        echo "<script>
                alert('Veículo excluído com sucesso!');
                window.location.href = '../index.php';
              </script>";

    } else {

        echo "<script>
                alert('Erro ao excluir o veículo.');
                window.location.href = '../index.php';
              </script>";
    }
}

?>