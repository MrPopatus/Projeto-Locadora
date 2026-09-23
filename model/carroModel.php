<?php

require_once __DIR__ . '/../controller/conexao.php';

class Carro
{

    public function inserir($modelo, $ano, $renavam, $valorDiaria, $idMarca, $imagemVeiculo)
    {
        try {

            $sql = "INSERT INTO veiculo 
                    (modelo, ano, renavam, valorDiaria, idMarcaV, imagemVeiculo)
                    VALUES (:m, :a, :r, :v, :marca, :imagem)";

            $conexao = Conexao::conectar();

            $stmt = $conexao->prepare($sql);

            $stmt->bindParam(":m", $modelo);
            $stmt->bindParam(":a", $ano);
            $stmt->bindParam(":r", $renavam);
            $stmt->bindParam(":v", $valorDiaria);
            $stmt->bindParam(":marca", $idMarca);
            $stmt->bindParam(":imagem", $imagemVeiculo);

            return $stmt->execute();

        } catch (PDOException $e) {

            error_log("Erro ao inserir veículo: " . $e->getMessage());

            return false;
        }
    }


    public function listarCarros()
    {
        try {

            $sql = "SELECT
                        v.idVeiculo,
                        v.modelo,
                        v.ano,
                        v.valorDiaria,
                        v.imagemVeiculo,
                        v.statusVeiculo,
                        m.nomeMarca
                    FROM veiculo v
                    INNER JOIN marca m
                        ON v.idMarcaV = m.idMarca";

            $conexao = Conexao::conectar();

            $stmt = $conexao->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {

            error_log("Erro ao listar carros: " . $e->getMessage());

            return [];
        }
    }


    public function buscarPorId($id)
    {
        try {

            $sql = "SELECT
                        v.idVeiculo,
                        v.modelo,
                        v.ano,
                        v.renavam,
                        v.valorDiaria,
                        v.imagemVeiculo,
                        v.statusVeiculo,
                        m.nomeMarca,
                        v.idMarcaV
                    FROM veiculo v
                    INNER JOIN marca m
                        ON v.idMarcaV = m.idMarca
                    WHERE v.idVeiculo = :id";

            $conexao = Conexao::conectar();

            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(":id", $id);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {

            error_log("Erro ao buscar veículo: " . $e->getMessage());

            return false;
        }
    }

    public function editar(
        $id,
        $modelo,
        $ano,
        $renavam,
        $valorDiaria,
        $idMarca,
        $imagemVeiculo,
        $statusVeiculo
    ) {
        try {

            $sql = "UPDATE veiculo
                    SET modelo = :modelo,
                        ano = :ano,
                        renavam = :renavam,
                        valorDiaria = :valor,
                        idMarcaV = :marca,
                        imagemVeiculo = :imagem,
                        statusVeiculo = :statusVeiculo
                    WHERE idVeiculo = :id";

            $conexao = Conexao::conectar();

            $stmt = $conexao->prepare($sql);

            $stmt->bindParam(":modelo", $modelo);
            $stmt->bindParam(":ano", $ano);
            $stmt->bindParam(":renavam", $renavam);
            $stmt->bindParam(":valor", $valorDiaria);
            $stmt->bindParam(":marca", $idMarca);
            $stmt->bindParam(":imagem", $imagemVeiculo);
            $stmt->bindParam(":statusVeiculo", $statusVeiculo);
            $stmt->bindParam(":id", $id);

            return $stmt->execute();

        } catch (PDOException $e) {

            die("Erro ao editar veículo: " . $e->getMessage());
        }
    }

    public function excluir($id)
    {
        try {

            $sql = "DELETE FROM veiculo WHERE idVeiculo = :id";

            $conexao = Conexao::conectar();

            $stmt = $conexao->prepare($sql);

            $stmt->bindParam(":id", $id);

            return $stmt->execute();

        } catch (PDOException $e) {

            error_log("Erro ao excluir veículo: " . $e->getMessage());

            return false;
        }
    }

    public function contarCarros()
    {
        try {
            $sql = "SELECT COUNT(*) AS total
                    FROM veiculo";

            $conexao = Conexao::conectar();

            $stmt = $conexao->prepare($sql);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return ['total' => 0];
        }
    }
    public function contarCarrosAtivos()
    {
        try {
            $sql = "SELECT COUNT(*) AS total
                    FROM veiculo
                    WHERE statusVeiculo = 'ativo'";

            $conexao = Conexao::conectar();

            $stmt = $conexao->prepare($sql);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return ['total' => 0];
        }
    }
    public function contarCarrosAlugados()
    {
        try {
            $sql = "SELECT COUNT(*) AS total
                    FROM veiculo
                    WHERE statusVeiculo = 'reservado'";

            $conexao = Conexao::conectar();

            $stmt = $conexao->prepare($sql);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return ['total' => 0];
        }
    }
    public function contarReceita()
    {
        try {
            $sql = "SELECT COALESCE(SUM(valorPagar), 0) AS total
                    FROM locacao";
            $conexao = Conexao::conectar();

            $stmt = $conexao->prepare($sql);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return ['total' => 0];
        }
    }
}
