<?php

class Conexao {
    public static function conectar() {
        $host = "localhost";
        $dbname = "locadora";
        $user = "root";
        $senha = "";

        try {
            $conn = new PDO("mysql:dbname=$dbname;host=$host", $user, $senha);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            if(isset($conn)){
                echo "Conexão realizada com sucesso no banco $dbname!";
                return $conn;
            }
        } catch (PDOException $e) {
            echo "Erro na conexão: " . $e->getMessage();
            return null;
        }
    }
}

?>
