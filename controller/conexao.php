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
                return $conn;
            }
        } catch (PDOException $e) {
            return null;
        }
    }
}

?>
