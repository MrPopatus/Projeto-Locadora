<?php
require_once '../controller/conexao.php';

class Cliente
{

    public function inserir($nomeCliente, $telefone, $email, $senha)
    {
        try {
            $sql = "INSERT INTO cliente (nomeCliente, telefone, email, senha, tipoUsuario) VALUES (:nomeCliente, :telefone, :email, :senha, 'cliente')";

            $conexao = Conexao::conectar();
            $stmt = $conexao->prepare($sql);

            $stmt->bindParam(":nomeCliente", $nomeCliente);
            $stmt->bindParam(":telefone", $telefone);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":senha", $senha);

            // Executa e retorna true em caso de sucesso
            return $stmt->execute();
        } catch (PDOException $e) {
            // Em ambiente de produção, grave no log em vez de dar echo no erro
            error_log("Erro ao inserir cliente: " . $e->getMessage());
            return false;
        }
    }
}
