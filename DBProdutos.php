<?php

require_once "Database.php";

class DBProdutos {
    private $conexao;
    private $tableName = 'produtos';

    public function __construct() {
        $db = new Database('localhost', 'banco_sistema', 'root', '');
        $this->conexao = $db->getConnection();
        $this->createTable();
    }

    public function createTable() {
        try {
            $sql = "CREATE TABLE IF NOT EXISTS " . $this->tableName . " (
                    prod_cod INT AUTO_INCREMENT PRIMARY KEY,
                    prod_descricao VARCHAR(30) NOT NULL,
                    pro_fabricante VARCHAR(15) NOT NULL,
                    pro_ingredientes VARCHAR(50) NOT NULL,
                    pro_orientacao VARCHAR(15) NOT NULL
                )";
            $this->conexao->exec($sql);
        } catch (PDOException $e) {
            echo "Erro ao criar a tabela: " . $e->getMessage();
        }
    }

    public function create($descricao, $fabricante, $ingredientes, $orientacao) {
        $sql = 'INSERT INTO ' . $this->tableName . ' (prod_descricao, pro_fabricante, pro_ingredientes, pro_orientacao) 
                VALUES (:descricao, :fabricante, :ingredientes, :orientacao)';
        try {
            $acesso = $this->conexao->prepare($sql);
            $acesso->bindParam(':descricao', $descricao);
            $acesso->bindParam(':fabricante', $fabricante);
            $acesso->bindParam(':ingredientes', $ingredientes);
            $acesso->bindParam(':orientacao', $orientacao);
            return $acesso->execute();
        } catch (PDOException $erro) {
            echo "Erro ao inserir na tabela Produtos: " . $erro->getMessage();
            return false;
        }
    }

    public function read($prod_cod) {
        $sql = "SELECT * FROM " . $this->tableName . " WHERE prod_cod = ?";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([$prod_cod]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($prod_cod, $descricao, $fabricante, $ingredientes, $orientacao) {
        $sql = 'UPDATE ' . $this->tableName . ' 
                SET prod_descricao = ?, pro_fabricante = ?, pro_ingredientes = ?, pro_orientacao = ? 
                WHERE prod_cod = ?';
        try {
            $stmt = $this->conexao->prepare($sql);
            return $stmt->execute([$descricao, $fabricante, $ingredientes, $orientacao, $prod_cod]);
        } catch (PDOException $erro) {
            echo "Erro ao atualizar o produto: " . $erro->getMessage();
            return false;
        }
    }

    public function delete($prod_cod) {
        $sql = 'DELETE FROM ' . $this->tableName . ' WHERE prod_cod = ?';
        try {
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute([$prod_cod]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $erro) {
            echo "Erro ao excluir o produto: " . $erro->getMessage();
            return false;
        }
    }
}

?>
