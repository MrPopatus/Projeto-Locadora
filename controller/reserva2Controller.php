<?php

session_start();

require_once '../model/reserva2Model.php';

if (isset($_POST['btnConfirmarLocacao'])) {

    if (!isset($_SESSION['reserva'])) {
        echo "<script>
                alert('Nenhuma reserva encontrada.');
                window.location.href = '../index.php';
              </script>";
        exit;
    }

    $cpf = preg_replace('/\D/', '', $_POST['cpf'] ?? '');
    $cnh = preg_replace('/\D/', '', $_POST['cnh'] ?? '');
    $numeroCartao = preg_replace('/\D/', '', $_POST['numeroCartao'] ?? '');

    $reserva = $_SESSION['reserva'];

    $idVeiculo = $reserva['idVeiculo'];
    $dataLocacao = $reserva['dataLocacao'];
    $dataDevolucaoPrevista = $reserva['dataDevolucaoPrevista'];
    $valorPagar = $reserva['valorPagar'];

    $idCliente = $_SESSION['usuario_id'];

    $reservaModel = new Reserva();

    if (strlen($cpf) !== 11 || strlen($cnh) !== 9 || strlen($numeroCartao) !== 16 || !$reservaModel->estaDisponivel($idVeiculo)) {
        echo "<script>alert('Confira seus dados ou a disponibilidade do veículo.'); window.location.href = '../view/cliente/reserva2View.php';</script>";
        exit;
    }

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

        $reservaModel->atualizarStatusVeiculo($idVeiculo, 'reservado');

        unset($_SESSION['reserva']);

        echo "<script>
                alert('Locação confirmada com sucesso!');
                window.location.href = '../index.php';
              </script>";

    } else {

        echo "<script>
                alert('Erro ao confirmar a locação. Tente novamente.');
                window.location.href = '../view/cliente/reserva2View.php';
              </script>";
    }
}
