<?php
session_start();
if (($_SESSION['usuario_tipo'] ?? '') !== 'funcionario') {
    header('Location: ../view/loginView.php');
    exit;
}

require_once __DIR__ . '/conexao.php';
$idLocacao = filter_input(INPUT_POST, 'idLocacao', FILTER_VALIDATE_INT);
if ($idLocacao) {
    $conexao = Conexao::conectar();
    try {
        $conexao->beginTransaction();
        $veiculo = $conexao->prepare('SELECT idVeiculoL FROM locacao WHERE idLocacao = :id AND statusLocacao IN (\'reservado\', \'ativo\') FOR UPDATE');
        $veiculo->execute([':id' => $idLocacao]);
        $idVeiculo = $veiculo->fetchColumn();
        if ($idVeiculo) {
            $conexao->prepare("UPDATE locacao SET statusLocacao = 'inativo', dataDevolucaoReal = CURRENT_DATE() WHERE idLocacao = :id")->execute([':id' => $idLocacao]);
            $conexao->prepare("UPDATE veiculo SET statusVeiculo = 'ativo' WHERE idVeiculo = :id")->execute([':id' => $idVeiculo]);
        }
        $conexao->commit();
    } catch (PDOException $e) {
        if ($conexao->inTransaction()) { $conexao->rollBack(); }
        error_log('Erro ao finalizar locação: ' . $e->getMessage());
    }
}
header('Location: ../view/administração/dashboardView.php');
exit;
