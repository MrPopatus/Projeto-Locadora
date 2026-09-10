<?php

require_once '../controller/conexao.php';

class Marca {

    public function inserir($nomeMarca) {

        try {

            $sql = "INSERT INTO marca (nomeMarca) VALUES (:n)";

            $conexao = Conexao::conectar();
            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(":n", $nomeMarca);

            return $stmt->execute();

        } catch (PDOException $e) {

            error_log("Erro ao inserir marca: " . $e->getMessage());
            return false;
        }
    }


    public function listarMarcas() {

        try {
             $sql = "SELECT idMarca, nomeMarca 
                    FROM marca 
                    ORDER BY nomeMarca";

            $conexao = Conexao::conectar();

            $stmt = $conexao->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {

            error_log("Erro ao listar marcas: " . $e->getMessage());
            return [];
        }
    }
}

?>