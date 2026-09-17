<?php

require_once '../controller/conexao.php';

class Login {

   public function buscarPorEmail($email){
        try {

            $sql = "SELECT * FROM cliente
                    WHERE email = :email";

            $conexao = Conexao::conectar();

            $stmt = $conexao->prepare($sql);

            $stmt->bindParam(':email', $email);

            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {

            error_log("Erro ao buscar cliente: " . $e->getMessage());

            return false;
        }
   }
}
?>