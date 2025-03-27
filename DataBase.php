<?php
// Conexão com o banco de dados
class DataBase {
    private $pdo;

    public function __construct() {
        $host = '127.0.0.1';
        $db   = 'provap1';
        $user = 'root';
        $pass = '';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (\PDOException $e) {
            die("Erro de conexão: " . $e->getMessage());
        }
    }

    // Funções para manipulação do banco de dados
    public function createProduct($pro_descricao, $pro_fabricante, $pro_ingredientes, $pro_orientacoes) {
        $stmt = $this->pdo->prepare("INSERT INTO produtos (pro_descricao, pro_fabricante, pro_ingredientes, pro_orientacoes) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$pro_descricao, $pro_fabricante, $pro_ingredientes, $pro_orientacoes]);
    }

    public function readProduct($pro_cod) {
        $stmt = $this->pdo->prepare("SELECT * FROM produtos WHERE pro_cod = ?");
        $stmt->execute([$pro_cod]);
        return $stmt->fetch();
    }

    public function updateProduct($pro_cod, $pro_descricao, $pro_fabricante, $pro_ingredientes, $pro_orientacoes) {
        $stmt = $this->pdo->prepare("UPDATE produtos SET pro_descricao = ?, pro_fabricante = ?, pro_ingredientes = ?, pro_orientacoes = ? WHERE pro_cod = ?");
        return $stmt->execute([$pro_descricao, $pro_fabricante, $pro_ingredientes, $pro_orientacoes, $pro_cod]);
    }

    public function deleteProduct($pro_cod) {
        $stmt = $this->pdo->prepare("DELETE FROM produtos WHERE pro_cod = ?");
        return $stmt->execute([$pro_cod]);
    }
}
?>