<?php

require_once '../controller/conexao.php';

class Reserva
{
    public function inserir(
        $idCliente,
        $idVeiculo,
        $dataLocacao,
        $dataDevolucaoPrevista,
        $cnh,
        $cartaoNumero,
        $cpf,
        $valorPagar
    ) {
        try {

            $sql = "INSERT INTO locacao (
                        idClienteL,
                        idVeiculoL,
                        dataLocacao,
                        dataDevolucaoPrevista,
                        cnh,
                        cartaoNumero,
                        cpf,
                        valorPagar
                    )
                    VALUES (
                        :cliente,
                        :veiculo,
                        :dataLocacao,
                        :devolucao,
                        :cnh,
                        :cartao,
                        :cpf,
                        :valor
                    )";

            $conexao = Conexao::conectar();

            $stmt = $conexao->prepare($sql);

            $stmt->bindParam(":cliente", $idCliente);
            $stmt->bindParam(":veiculo", $idVeiculo);
            $stmt->bindParam(":dataLocacao", $dataLocacao);
            $stmt->bindParam(":devolucao", $dataDevolucaoPrevista);
            $stmt->bindParam(":cnh", $cnh);
            $stmt->bindParam(":cartao", $cartaoNumero);
            $stmt->bindParam(":cpf", $cpf);
            $stmt->bindParam(":valor", $valorPagar);

            return $stmt->execute();

        } catch (PDOException $e) {

            error_log("Erro ao inserir locação: " . $e->getMessage());

            return false;
        }
    }

    public function estaDisponivel($idVeiculo)
{
    try {

        $sql = "SELECT COUNT(*)
                FROM locacao
                WHERE idVeiculoL = :idVeiculo
                AND statusLocacao IN ('reservado', 'ativo')";

        $conexao = Conexao::conectar();

        $stmt = $conexao->prepare($sql);

        $stmt->bindParam(":idVeiculo", $idVeiculo);

        $stmt->execute();

        $quantidade = $stmt->fetchColumn();

        return $quantidade == 0;

    } catch (PDOException $e) {

        error_log("Erro ao verificar disponibilidade: " . $e->getMessage());

        return false;
    }
}
}