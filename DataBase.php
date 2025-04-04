<?php
class DataBase { //Variáveis que armazenam as informações para conexão  
    private $host = 'localhost';
    private $db_name = 'provap1';
    private $username = 'root';
    private $password = '';
    private $DBConn;

    // Construtor para passar parâmetros para definir as configurações do banco de dados
    public function __construct($servidor = null, $nomeBanco = null, $usuario = null, $senha = null) {
        $this->host = $servidor ?? $this->host;
        $this->db_name = $nomeBanco ?? $this->db_name;
        $this->username = $usuario ?? $this->username;
        $this->password = $senha ?? $this->password;
    }

    public function getConnection() { //Estabelece a conexão com o banco de dados
        $this->DBConn = null;
        try {
            $this->DBConn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
            $this->DBConn->exec('set names utf8');
            $this->DBConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $e) {
            error_log("Erro ao conectar-se ao banco: " .$e->getMessage());
            die("Falha na conexão. Por favor, tente novamente mais tarde.");
        }
        return $this->DBConn;
    }
}
?>