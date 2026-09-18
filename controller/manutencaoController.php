<?php

require_once '../model/manutencaoModel.php';
require_once '../model/carroModel.php';

if (isset($_POST['btnAgendar'])) {

        $idVeiculo = $_POST['veiculo'];
        $tipo = $_POST['tipo'];
        $descricao = $_POST['descricao'];
        $dataInicio = $_POST['dataInicio'];
        $dataFim = $_POST['dataFim'];
        $custo = $_POST['custo'];
        $status = $_POST['status'];

    $manutencaoModel = new Manutencao();
    $sucesso = $manutencaoModel->inserir($idVeiculo, $tipo, $descricao, $dataInicio, $dataFim, $custo, $status);

    if ($sucesso) {
        echo "<script>
                    alert('Agendamento realizado com sucesso!');
                    window.location.href = '../view/manutencaoView.php';
                </script>";


    } else {
        echo "<script>
                    alert('Erro ao realizar o agendamento. Tente novamente.');
                    window.location.href = '../view/manutencaoView.php';
                </script>";
    }
}

if (isset($_POST['btnAtualizarStatus'])) {
    $idManutencao = filter_input(INPUT_POST, 'idManutencao', FILTER_VALIDATE_INT);
    $status = $_POST['status'] ?? '';
    $statusPermitidos = ['agendada', 'em_andamento', 'finalizada'];

    if (!$idManutencao || !in_array($status, $statusPermitidos, true)) {
        echo "<script>alert('Dados de status inválidos.'); window.location.href = '../view/manutencaoView.php';</script>";
        exit;
    }

    $manutencaoModel = new Manutencao();
    $sucesso = $manutencaoModel->editarStatus($idManutencao, $status);
    $mensagem = $sucesso ? 'Status atualizado com sucesso!' : 'Erro ao atualizar o status. Tente novamente.';

    echo "<script>alert('{$mensagem}'); window.location.href = '../view/manutencaoView.php';</script>";
}
