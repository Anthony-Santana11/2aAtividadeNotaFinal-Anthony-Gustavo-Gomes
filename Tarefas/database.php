<?php
class Database {
    private $db;
    private $dbFile;

    public function __construct() {
        try {
            
            $this->dbFile = __DIR__ . '/tarefas.db';
            
            
            $this->db = new SQLite3($this->dbFile);
            
            
            $this->criarTabelaSeNaoExistir();
            
        } catch (Exception $e) {
            echo "Erro ao conectar: " . $e->getMessage();
            die();
        }
    }

    private function criarTabelaSeNaoExistir() {
        $query = "CREATE TABLE IF NOT EXISTS tarefas (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            descricao TEXT NOT NULL,
            data_vencimento DATE NOT NULL,
            concluida INTEGER DEFAULT 0,
            data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP
        )";
        $this->db->exec($query);
    }

    public function getTarefas() {
        return $this->db->query("SELECT * FROM tarefas ORDER BY data_vencimento ASC");
    }

    public function getTarefasPendentes() {
        return $this->db->query("SELECT * FROM tarefas WHERE concluida = 0 ORDER BY data_vencimento ASC");
    }

    public function getTarefasConcluidas() {
        return $this->db->query("SELECT * FROM tarefas WHERE concluida = 1 ORDER BY data_vencimento ASC");
    }

    public function adicionarTarefa($descricao, $data_vencimento) {
        $stmt = $this->db->prepare("INSERT INTO tarefas (descricao, data_vencimento) VALUES (:desc, :data)");
        $stmt->bindValue(':desc', $descricao, SQLITE3_TEXT);
        $stmt->bindValue(':data', $data_vencimento, SQLITE3_TEXT);
        return $stmt->execute();
    }

    public function marcarComoConcluida($id) {
        $stmt = $this->db->prepare("UPDATE tarefas SET concluida = 1 WHERE id = :id");
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        return $stmt->execute();
    }

    public function deletarTarefa($id) {
        $stmt = $this->db->prepare("DELETE FROM tarefas WHERE id = :id");
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        return $stmt->execute();
    }

    public function __destruct() {
        if ($this->db) {
            $this->db->close();
        }
    }
}
?> 