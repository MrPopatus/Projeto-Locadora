<?php

require_once '../model/marcaModel.php';
require_once '../model/carroModel.php';

function salvarImagemVeiculo($campo)
{
    if (!isset($_FILES[$campo]) || $_FILES[$campo]['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if ($_FILES[$campo]['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Ocorreu um erro ao enviar a imagem.');
    }

    if ($_FILES[$campo]['size'] > 5 * 1024 * 1024) {
        throw new RuntimeException('A imagem deve ter no máximo 5 MB.');
    }

    $tiposPermitidos = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp'
    ];

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $tipoImagem = $finfo->file($_FILES[$campo]['tmp_name']);

    if (!isset($tiposPermitidos[$tipoImagem])) {
        throw new RuntimeException('Envie uma imagem JPG, PNG ou WEBP.');
    }

    $nomeArquivo = bin2hex(random_bytes(16)) . '.' . $tiposPermitidos[$tipoImagem];
    $diretorio = __DIR__ . '/../img/veiculos/';

    if (!is_dir($diretorio) && !mkdir($diretorio, 0755, true)) {
        throw new RuntimeException('Não foi possível preparar a pasta das imagens.');
    }

    if (!move_uploaded_file($_FILES[$campo]['tmp_name'], $diretorio . $nomeArquivo)) {
        throw new RuntimeException('Não foi possível salvar a imagem.');
    }

    return 'img/veiculos/' . $nomeArquivo;
}

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

    try {
        $imagemVeiculo = salvarImagemVeiculo('imagemVeiculo');

        if ($imagemVeiculo === null) {
            throw new RuntimeException('Selecione uma imagem para o veículo.');
        }
    } catch (RuntimeException $e) {
        echo "<script>
                alert(" . json_encode($e->getMessage()) . ");
                window.history.back();
              </script>";
        exit();
    }

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
                window.location.href = '../index.php';
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

    $carroModel = new Carro();

    try {
        $imagemVeiculo = salvarImagemVeiculo('imagemVeiculo');

        if ($imagemVeiculo === null) {
            $carroAtual = $carroModel->buscarPorId($id);
            $imagemVeiculo = $carroAtual['imagemVeiculo'] ?? null;
        }

        if ($imagemVeiculo === null) {
            throw new RuntimeException('Não foi possível localizar a imagem atual do veículo.');
        }
    } catch (RuntimeException $e) {
        echo "<script>
                alert(" . json_encode($e->getMessage()) . ");
                window.history.back();
              </script>";
        exit();
    }

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
