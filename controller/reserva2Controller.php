<?php

session_start();

require_once '../model/reserva2Model.php';

$reservaModel = new Reserva();

$disponivel = $reservaModel->estaDisponivel($idVeiculo);


if (!$disponivel) {

    unset($_SESSION['reserva']);

    echo "<script>
            alert('Este veículo já foi reservado.');
            window.location.href = '../index.php';
          </script>";

    exit;
}

if (isset($_POST['btnConfirmarLocacao'])) {

    if (!isset($_SESSION['reserva'])) {
        echo "<script>
                alert('Nenhuma reserva encontrada.');
                window.location.href = '../index.php';
              </script>";
        exit;
    }

    $cpf = $_POST['cpf'];
    $cnh = $_POST['cnh'];
    $numeroCartao = $_POST['numeroCartao'];

    $reserva = $_SESSION['reserva'];

    $idVeiculo = $reserva['idVeiculo'];
    $dataLocacao = $reserva['dataLocacao'];
    $dataDevolucaoPrevista = $reserva['dataDevolucaoPrevista'];
    $valorPagar = $reserva['valorPagar'];

    $idCliente = $_SESSION['usuario_id'];

    $reservaModel = new Reserva();

    $sucesso = $reservaModel->inserir(
        $idCliente,
        $idVeiculo,
        $dataLocacao,
        $dataDevolucaoPrevista,
        $cnh,
        $numeroCartao,
        $cpf,
        $valorPagar
    );

    if ($sucesso) {

        unset($_SESSION['reserva']);

        echo "<script>
                alert('Locação confirmada com sucesso!');
                window.location.href = '../index.php';
              </script>";

    } else {

        echo "<script>
                alert('Erro ao confirmar a locação. Tente novamente.');
                window.location.href = '../view/reserva2View.php';
              </script>";
    }
}
