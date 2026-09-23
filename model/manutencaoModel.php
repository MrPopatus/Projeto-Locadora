<?php
require_once __DIR__ . '/../controller/conexao.php';

class Manutencao
    {

        public function inserir(
        $idVeiculo,
        $tipo,
        $descricao,
        $dataInicio,
        $dataFim,
        $custo,
        $status
    ) {
        try {
            $sql = "INSERT INTO manutencao
                    (idVeiculoM, tipo, descricao, dataInicio, dataFim, custo, status)
                    VALUES
                    (:veiculo, :tipo, :descricao, :inicio, :fim, :custo, :status)";

            $conexao = Conexao::conectar();

            $stmt = $conexao->prepare($sql);

            $stmt->bindParam(':veiculo', $idVeiculo);
            $stmt->bindParam(':tipo', $tipo);
            $stmt->bindParam(':descricao', $descricao);
            $stmt->bindParam(':inicio', $dataInicio);
            $stmt->bindParam(':fim', $dataFim);
            $stmt->bindParam(':custo', $custo);
            $stmt->bindParam(':status', $status);

            return $stmt->execute();

        } catch (PDOException $e) {
            error_log('Erro ao registrar manutenção: ' . $e->getMessage());
            return false;
        }
    }
    public function contarAgendadas()
    {
        try {
            $sql = "SELECT COUNT(*) AS total
                    FROM manutencao
                    WHERE status = 'agendada'";

            $conexao = Conexao::conectar();

            $stmt = $conexao->prepare($sql);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return ['total' => 0];
        }
    }
    public function contarEmAndamento()
    {
        try {
            $sql = "SELECT COUNT(DISTINCT idVeiculoM) AS total
                    FROM manutencao
                    WHERE status = 'em_andamento'";

            $conexao = Conexao::conectar();

            $stmt = $conexao->prepare($sql);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return ['total' => 0];
        }
    }
    public function contarFinalizada()
    {
        try {
            $sql = "SELECT COUNT(*) AS total
                    FROM manutencao
                    WHERE status = 'finalizada'
                      AND MONTH(dataFim) = MONTH(CURRENT_DATE())
                      AND YEAR(dataFim) = YEAR(CURRENT_DATE())";

            $conexao = Conexao::conectar();

            $stmt = $conexao->prepare($sql);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return ['total' => 0];
        }
    }

    public function listarManutencoes()
    {
        try {
            $sql = "SELECT
                        m.idManutencao,
                        m.tipo,
                        m.descricao,
                        m.dataInicio,
                        m.dataFim,
                        m.custo,
                        m.status,
                        v.modelo,
                        ma.nomeMarca
                    FROM manutencao m
                    INNER JOIN veiculo v ON m.idVeiculoM = v.idVeiculo
                    INNER JOIN marca ma ON v.idMarcaV = ma.idMarca
                    ORDER BY m.dataInicio DESC";

            $conexao = Conexao::conectar();
            $stmt = $conexao->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log('Erro ao listar manutenções: ' . $e->getMessage());
            return [];
        }
    }
    public function contarCusto()
    {
        try {
            $sql = "SELECT COALESCE(SUM(custo), 0) AS total
                    FROM manutencao
                    WHERE status = 'finalizada'
                      AND MONTH(dataFim) = MONTH(CURRENT_DATE())
                      AND YEAR(dataFim) = YEAR(CURRENT_DATE())";

            $conexao = Conexao::conectar();

            $stmt = $conexao->prepare($sql);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return ['total' => 0];
        }
    }
    public function editarStatus(
        $idManutencao,
        $status
    ) {
        try {
            $sql = "UPDATE manutencao
                    SET status = :status
                    WHERE idManutencao = :idManutencao";

            $conexao = Conexao::conectar();

            $stmt = $conexao->prepare($sql);

            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':idManutencao', $idManutencao, PDO::PARAM_INT);

            return $stmt->execute();

        } catch (PDOException $e) {
            error_log('Erro ao atualizar status do agendamento: ' . $e->getMessage());
            return false;
        }
    }

}
