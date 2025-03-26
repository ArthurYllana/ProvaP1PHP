<?php

class Database {
    // Configurações do banco de dados
    private $host = 'localhost';
    private $db_name = 'banco_sistema';
    private $username = 'root';
    private $password = '';
    private $DBConn; // Conexão com o banco

    public function __construct($servidor, $nomeBanco, $usuario, $senha) {
        $this->host = $servidor;
        $this->db_name = $nomeBanco;
        $this->username = $usuario;
        $this->password = $senha;
        $this->createDatabase(); // Criar banco se não existir
    }

    // Criar database caso não exista
    private function createDatabase() {
        try {
            // Conexão inicial sem banco selecionado
            $tempConn = new PDO("mysql:host={$this->host}", $this->username, $this->password);
            $tempConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $tempConn->exec("CREATE DATABASE IF NOT EXISTS {$this->db_name} CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
            echo "Banco de dados '{$this->db_name}' criado ou já existente.\n";

            // Agora conectar diretamente ao banco criado
            $this->DBConn = new PDO("mysql:host={$this->host};dbname={$this->db_name}", $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
            ]);
        } catch (PDOException $e) {
            die("Erro ao criar o banco de dados: " . $e->getMessage());
        }
    }

    // Criar conexão com o banco
    public function getConnection() {
        if (!$this->DBConn) {
            try {
                $this->DBConn = new PDO("mysql:host={$this->host};dbname={$this->db_name}", $this->username, $this->password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
                ]);
            } catch (PDOException $e) {
                die("Erro na conexão com o banco de dados: " . $e->getMessage());
            }
        }
        return $this->DBConn;
    }
}

?>
