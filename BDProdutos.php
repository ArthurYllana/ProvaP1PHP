<?php
require_once "DataBase.php";

class DBProdutos {
    private $conexao;
    private $tableName = 'produtos';

    public function __construct() {
        $db = new DataBase();
        $this->conexao = $db->getConnection();
    }

    // Método para criar um novo produto
    public function create($descricao, $fabricante, $ingredientes, $orientacoes) {
        $sql = 'INSERT INTO ' . $this->tableName . ' (pro_descricao, pro_fabricante, pro_ingredientes, pro_orientacoes) VALUES (?, ?, ?, ?)';
        try {
            $stmt = $this->conexao->prepare($sql);
            return $stmt->execute([$descricao, $fabricante, $ingredientes, $orientacoes]);
        } catch (PDOException $erro) {
            throw new Exception("Erro ao criar produto: " . $erro->getMessage());
        }
    }

    // Método para recuperar um produto por ID
    public function recoveryById($id) {
        $sql = 'SELECT * FROM ' . $this->tableName . ' WHERE pro_cod = ?';
        try {
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $erro) {
            throw new Exception("Erro ao recuperar produto: " . $erro->getMessage());
        }
    }

    // Método para atualizar um produto
    public function update($id, $descricao, $fabricante, $ingredientes, $orientacoes) {
        $sql = 'UPDATE ' . $this->tableName . ' SET pro_descricao = ?, pro_fabricante = ?, pro_ingredientes = ?, pro_orientacoes = ? WHERE pro_cod = ?';
        try {
            $stmt = $this->conexao->prepare($sql);
            return $stmt->execute([$descricao, $fabricante, $ingredientes, $orientacoes, $id]);
        } catch (PDOException $erro) {
            throw new Exception("Erro ao atualizar produto: " . $erro->getMessage());
        }
    }

    // Método para deletar um produto
    public function delete($id) {
        $sql = 'DELETE FROM ' . $this->tableName . ' WHERE pro_cod = ?';
        try {
            $stmt = $this->conexao->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $erro) {
            throw new Exception("Erro ao deletar produto: " . $erro->getMessage());
        }
    }
    // Método para buscar a descrição de um produto
    public function searchByDescription($term) {
        $sql = 'SELECT * FROM produtos WHERE pro_descricao LIKE ? ORDER BY pro_cod';
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute(['%' . $term . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para listar todos os produtos
    public function listAll() {
        $sql = 'SELECT * FROM ' . $this->tableName . ' ORDER BY pro_cod';
        try {
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $erro) {
            throw new Exception("Erro ao listar produtos: " . $erro->getMessage());
        }
    }
}
?>