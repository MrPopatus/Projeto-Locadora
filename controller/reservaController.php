<?php

session_start();

require_once '../model/carroModel.php';

if (isset($_POST['btnContinuarReserva'])) {

    $idVeiculo = $_POST['idVeiculo'];
    $dataDevolucaoPrevista = $_POST['dataDevolucaoPrevista'];

    $carroModel = new Carro();

    $carro = $carroModel->buscarPorId($idVeiculo);

    if (!$carro) {
        echo "<script>
                alert('Veículo não encontrado.');
                window.location.href = '../index.php';
              </script>";
        exit;
    }

    $valorDiaria = $carro['valorDiaria'];

    date_default_timezone_set('America/Sao_Paulo');

    $dataAtual = new DateTimeImmutable('today');

    $dataDevolucao = DateTimeImmutable::createFromFormat(
        '!Y-m-d',
        $dataDevolucaoPrevista
    );

    if (!$dataDevolucao) {
        echo "<script>
                alert('Data de devolução inválida.');
                history.back();
              </script>";
        exit;
    }

    $intervalo = $dataAtual->diff($dataDevolucao);

    $quantidadeDias = (int) $intervalo->format('%r%a');

    if ($quantidadeDias <= 0) {
        echo "<script>
                alert('A data de devolução deve ser posterior à data atual.');
                history.back();
              </script>";
        exit;
    }

    $valorPagar = $quantidadeDias * $valorDiaria;

    echo "<pre>";
    echo "Veículo: " . $idVeiculo . "\n";
    echo "Data de locação: " . $dataAtual->format('Y-m-d') . "\n";
    echo "Data de devolução: " . $dataDevolucaoPrevista . "\n";
    echo "Quantidade de dias: " . $quantidadeDias . "\n";
    echo "Valor da diária: R$ " . $valorDiaria . "\n";
    echo "Valor total: R$ " . $valorPagar;
    echo "</pre>";

    $_SESSION['reserva'] = [
    'idVeiculo' => $idVeiculo,
    'dataLocacao' => $dataAtual->format('Y-m-d'),
    'dataDevolucaoPrevista' => $dataDevolucaoPrevista,
    'quantidadeDias' => $quantidadeDias,
    'valorDiaria' => $valorDiaria,
    'valorPagar' => $valorPagar
    ];

    header("Location: ../view/cliente/reserva2View.php");
        exit;
    }

?>
