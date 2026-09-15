<?php
require_once '../controller/conexao.php';

class Cliente
{

    public function inserir($nomeCliente, $telefone, $email, $senha)
    {
        try {
            $sql = "INSERT INTO cliente (nomeCliente, telefone, email, senha) VALUES (:n, :t, :c, :e)";

            $conexao = Conexao::conectar();
            $stmt = $conexao->prepare($sql);

            $stmt->bindParam(":n", $nomeCliente);
            $stmt->bindParam(":t", $telefone);
            $stmt->bindParam(":c", $email);
            $stmt->bindParam(":e", $senha);

            // Executa e retorna true em caso de sucesso
            return $stmt->execute();
        } catch (PDOException $e) {
            // Em ambiente de produção, grave no log em vez de dar echo no erro
            error_log("Erro ao inserir cliente: " . $e->getMessage());
            return false;
        }
    }
}
