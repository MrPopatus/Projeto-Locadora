<?php

require_once __DIR__ . '/../controller/conexao.php';

class Dashboard
{
    private function consultar($sql)
    {
        $stmt = Conexao::conectar()->prepare($sql);
        $stmt->execute();
        return $stmt;
    }

    public function resumo()
    {
        $sql = "SELECT
                    (SELECT COUNT(*) FROM veiculo) AS totalVeiculos,
                    (SELECT COUNT(*) FROM veiculo WHERE statusVeiculo = 'ativo') AS veiculosDisponiveis,
                    (SELECT COUNT(*) FROM locacao WHERE statusLocacao IN ('reservado', 'ativo')) AS locacoesAtivas,
                    (SELECT COALESCE(SUM(valorPagar), 0) FROM locacao WHERE MONTH(dataLocacao) = MONTH(CURRENT_DATE()) AND YEAR(dataLocacao) = YEAR(CURRENT_DATE())) AS receitaMes,
                    (SELECT COALESCE(SUM(custo), 0) FROM manutencao WHERE status = 'finalizada' AND MONTH(dataFim) = MONTH(CURRENT_DATE()) AND YEAR(dataFim) = YEAR(CURRENT_DATE())) AS custoMes";
        return $this->consultar($sql)->fetch(PDO::FETCH_ASSOC);
    }

    public function statusFrota()
    {
        $resultado = ['ativo' => 0, 'reservado' => 0, 'inativo' => 0, 'cancelado' => 0];
        foreach ($this->consultar("SELECT statusVeiculo, COUNT(*) AS total FROM veiculo GROUP BY statusVeiculo")->fetchAll(PDO::FETCH_ASSOC) as $item) {
            $resultado[$item['statusVeiculo']] = (int) $item['total'];
        }
        return $resultado;
    }

    public function devolucoesHoje()
    {
        $sql = "SELECT c.nomeCliente, m.nomeMarca, v.modelo FROM locacao l INNER JOIN cliente c ON c.idCliente = l.idClienteL INNER JOIN veiculo v ON v.idVeiculo = l.idVeiculoL INNER JOIN marca m ON m.idMarca = v.idMarcaV WHERE l.dataDevolucaoPrevista = CURRENT_DATE() AND l.statusLocacao IN ('reservado', 'ativo') ORDER BY c.nomeCliente";
        return $this->consultar($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function manutencoesProximas()
    {
        $sql = "SELECT mt.tipo, mt.dataInicio, mt.status, m.nomeMarca, v.modelo FROM manutencao mt INNER JOIN veiculo v ON v.idVeiculo = mt.idVeiculoM INNER JOIN marca m ON m.idMarca = v.idMarcaV WHERE mt.status IN ('agendada', 'em_andamento') AND mt.dataInicio BETWEEN CURRENT_DATE() AND DATE_ADD(CURRENT_DATE(), INTERVAL 7 DAY) ORDER BY mt.dataInicio ASC LIMIT 4";
        return $this->consultar($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function reservasRecentes()
    {
        $sql = "SELECT l.idLocacao, l.dataLocacao, l.statusLocacao, c.nomeCliente, m.nomeMarca, v.modelo FROM locacao l INNER JOIN cliente c ON c.idCliente = l.idClienteL INNER JOIN veiculo v ON v.idVeiculo = l.idVeiculoL INNER JOIN marca m ON m.idMarca = v.idMarcaV ORDER BY l.idLocacao DESC LIMIT 5";
        return $this->consultar($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}
