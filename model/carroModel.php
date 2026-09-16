<?php

require_once '../controller/conexao.php';

class Carro
{

    public function inserir($modelo, $ano, $renavam, $valorDiaria, $idMarca)
    {

        try {

            $sql = "INSERT INTO veiculo (modelo, ano, renavam, valorDiaria, idMarcaV) 
                    VALUES (:m, :a, :r, :v, :marca)";

            $conexao = Conexao::conectar();

            $stmt = $conexao->prepare($sql);

            $stmt->bindParam(":m", $modelo);
            $stmt->bindParam(":a", $ano);
            $stmt->bindParam(":r", $renavam);
            $stmt->bindParam(":v", $valorDiaria);
            $stmt->bindParam(":marca", $idMarca);

            return $stmt->execute();
        } catch (PDOException $e) {

            error_log("Erro ao inserir veículo: " . $e->getMessage());

            return false;
        }
    }

    public function listarCarros() {

        try {
             $sql = "SELECT *  
                    FROM veiculo 
                    ORDER BY modelo";

            $conexao = Conexao::conectar();

            $stmt = $conexao->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {

            error_log("Erro ao listar carros: " . $e->getMessage());
            return [];
        }
    }
}
