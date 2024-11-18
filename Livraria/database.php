<?php

class Database {
    private $db_file = "livraria.sqlite";
    private $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            // Cria/conecta ao banco SQLite
            $this->conn = new PDO("sqlite:" . $this->db_file);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Cria a tabela se não existir
            $this->conn->exec("CREATE TABLE IF NOT EXISTS livros (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                titulo TEXT NOT NULL,
                autor TEXT NOT NULL,
                ano_publicacao INTEGER
            )");
            
        } catch(PDOException $e) {
            echo "Erro na conexão: " . $e->getMessage();
        }

        return $this->conn;
    }
}
?>
