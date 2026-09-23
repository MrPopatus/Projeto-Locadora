<?php

require_once __DIR__ . '/../controller/conexao.php';

class Atendimento
{
    public function buscarAbertoPorCliente($idCliente)
    {
        try {
            $sql = "SELECT * FROM atendimento
                    WHERE idClienteA = :idCliente
                      AND statusAtendimento = 'aberto'
                    ORDER BY idAtendimento DESC
                    LIMIT 1";
            $stmt = Conexao::conectar()->prepare($sql);
            $stmt->bindParam(':idCliente', $idCliente, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erro ao buscar atendimento: ' . $e->getMessage());
            return false;
        }
    }

    public function buscarPorId($idAtendimento)
    {
        try {
            $sql = "SELECT a.*, c.nomeCliente
                    FROM atendimento a
                    INNER JOIN cliente c ON c.idCliente = a.idClienteA
                    WHERE a.idAtendimento = :idAtendimento";
            $stmt = Conexao::conectar()->prepare($sql);
            $stmt->bindParam(':idAtendimento', $idAtendimento, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erro ao buscar atendimento: ' . $e->getMessage());
            return false;
        }
    }

    public function criar($idCliente)
    {
        try {
            $conexao = Conexao::conectar();
            $stmt = $conexao->prepare("INSERT INTO atendimento (idClienteA) VALUES (:idCliente)");
            $stmt->bindParam(':idCliente', $idCliente, PDO::PARAM_INT);
            if (!$stmt->execute()) {
                return false;
            }
            return (int) $conexao->lastInsertId();
        } catch (PDOException $e) {
            error_log('Erro ao criar atendimento: ' . $e->getMessage());
            return false;
        }
    }

    public function inserirMensagem($idAtendimento, $idRemetente, $tipoRemetente, $conteudo)
    {
        try {
            $sql = "INSERT INTO mensagem
                        (idAtendimentoM, idRemetente, tipoRemetente, conteudo)
                    VALUES (:idAtendimento, :idRemetente, :tipoRemetente, :conteudo)";
            $stmt = Conexao::conectar()->prepare($sql);
            $stmt->bindParam(':idAtendimento', $idAtendimento, PDO::PARAM_INT);
            $stmt->bindParam(':idRemetente', $idRemetente, PDO::PARAM_INT);
            $stmt->bindParam(':tipoRemetente', $tipoRemetente);
            $stmt->bindParam(':conteudo', $conteudo);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Erro ao enviar mensagem: ' . $e->getMessage());
            return false;
        }
    }

    public function atribuirFuncionario($idAtendimento, $idFuncionario)
    {
        try {
            $sql = "UPDATE atendimento
                    SET idFuncionarioA = COALESCE(idFuncionarioA, :idFuncionario)
                    WHERE idAtendimento = :idAtendimento
                      AND statusAtendimento = 'aberto'";
            $stmt = Conexao::conectar()->prepare($sql);
            $stmt->bindParam(':idFuncionario', $idFuncionario, PDO::PARAM_INT);
            $stmt->bindParam(':idAtendimento', $idAtendimento, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Erro ao atribuir funcionário: ' . $e->getMessage());
            return false;
        }
    }

    public function encerrar($idAtendimento, $idFuncionario)
    {
        try {
            $sql = "UPDATE atendimento
                    SET statusAtendimento = 'encerrado',
                        dataEncerramento = NOW(),
                        idFuncionarioA = COALESCE(idFuncionarioA, :idFuncionario)
                    WHERE idAtendimento = :idAtendimento
                      AND statusAtendimento = 'aberto'";
            $stmt = Conexao::conectar()->prepare($sql);
            $stmt->bindParam(':idFuncionario', $idFuncionario, PDO::PARAM_INT);
            $stmt->bindParam(':idAtendimento', $idAtendimento, PDO::PARAM_INT);
            return $stmt->execute() && $stmt->rowCount() === 1;
        } catch (PDOException $e) {
            error_log('Erro ao encerrar atendimento: ' . $e->getMessage());
            return false;
        }
    }

    public function listarMensagens($idAtendimento)
    {
        try {
            $sql = "SELECT m.*, c.nomeCliente AS nomeRemetente
                    FROM mensagem m
                    INNER JOIN cliente c ON c.idCliente = m.idRemetente
                    WHERE m.idAtendimentoM = :idAtendimento
                    ORDER BY m.dataEnvio ASC, m.idMensagem ASC";
            $stmt = Conexao::conectar()->prepare($sql);
            $stmt->bindParam(':idAtendimento', $idAtendimento, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erro ao listar mensagens: ' . $e->getMessage());
            return [];
        }
    }

    public function listarAtendimentos()
    {
        try {
            $sql = "SELECT a.idAtendimento, a.statusAtendimento, a.dataAbertura,
                           c.nomeCliente, MAX(m.dataEnvio) AS ultimaMensagem
                    FROM atendimento a
                    INNER JOIN cliente c ON c.idCliente = a.idClienteA
                    LEFT JOIN mensagem m ON m.idAtendimentoM = a.idAtendimento
                    GROUP BY a.idAtendimento, a.statusAtendimento, a.dataAbertura, c.nomeCliente
                    ORDER BY (a.statusAtendimento = 'aberto') DESC,
                             COALESCE(MAX(m.dataEnvio), a.dataAbertura) DESC";
            $stmt = Conexao::conectar()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erro ao listar atendimentos: ' . $e->getMessage());
            return [];
        }
    }
}
