<?php
require_once __DIR__ . '/../controller/conexao.php';

class Cliente
{

    public function inserir($nomeCliente, $telefone, $email, $senhaHash)
    {
        try {
            $sql = "INSERT INTO cliente (nomeCliente, telefone, email, senha, tipoUsuario) VALUES (:nomeCliente, :telefone, :email, :senha, 'cliente')";

            $conexao = Conexao::conectar();
            $stmt = $conexao->prepare($sql);

            $stmt->bindParam(":nomeCliente", $nomeCliente);
            $stmt->bindParam(":telefone", $telefone);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":senha", $senhaHash);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao inserir cliente: " . $e->getMessage());
            return false;
        }
    }

    public function listarClientes()
    {
        try {
            $sql = "SELECT
                        c.idCliente,
                        c.nomeCliente,
                        c.telefone,
                        c.email,
                        COUNT(l.idLocacao) AS totalLocacoes,
                        SUM(CASE WHEN l.statusLocacao IN ('reservado', 'ativo') THEN 1 ELSE 0 END) AS locacoesAtivas,
                        MAX(l.dataLocacao) AS ultimaLocacao
                    FROM cliente c
                    LEFT JOIN locacao l ON l.idClienteL = c.idCliente
                    WHERE c.tipoUsuario = 'cliente'
                    GROUP BY c.idCliente, c.nomeCliente, c.telefone, c.email
                    ORDER BY c.nomeCliente ASC";

            $conexao = Conexao::conectar();
            $stmt = $conexao->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erro ao listar clientes: ' . $e->getMessage());
            return [];
        }
    }

    public function buscarResumo()
    {
        try {
            $sql = "SELECT
                        COUNT(*) AS totalClientes,
                        COUNT(DISTINCT CASE WHEN l.statusLocacao IN ('reservado', 'ativo') THEN c.idCliente END) AS clientesAtivos,
                        COUNT(DISTINCT CASE WHEN l.idLocacao IS NOT NULL THEN c.idCliente END) AS clientesComHistorico,
                        COALESCE(SUM(l.valorPagar), 0) AS receitaTotal
                    FROM cliente c
                    LEFT JOIN locacao l ON l.idClienteL = c.idCliente
                    WHERE c.tipoUsuario = 'cliente'";

            $conexao = Conexao::conectar();
            $stmt = $conexao->prepare($sql);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC) ?: [
                'totalClientes' => 0,
                'clientesAtivos' => 0,
                'clientesComHistorico' => 0,
                'receitaTotal' => 0,
            ];
        } catch (PDOException $e) {
            error_log('Erro ao buscar resumo de clientes: ' . $e->getMessage());
            return [
                'totalClientes' => 0,
                'clientesAtivos' => 0,
                'clientesComHistorico' => 0,
                'receitaTotal' => 0,
            ];
        }
    }

    public function listarHistoricoLocacoes($idCliente)
    {
        try {
            $sql = "SELECT
                        l.idLocacao,
                        l.dataLocacao,
                        l.dataDevolucaoPrevista,
                        l.dataDevolucaoReal,
                        l.valorPagar,
                        l.statusLocacao,
                        v.modelo,
                        ma.nomeMarca
                    FROM locacao l
                    INNER JOIN veiculo v ON v.idVeiculo = l.idVeiculoL
                    INNER JOIN marca ma ON ma.idMarca = v.idMarcaV
                    WHERE l.idClienteL = :idCliente
                    ORDER BY l.dataLocacao DESC";

            $conexao = Conexao::conectar();
            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(':idCliente', $idCliente, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erro ao listar histórico de locações: ' . $e->getMessage());
            return [];
        }
    }
}
