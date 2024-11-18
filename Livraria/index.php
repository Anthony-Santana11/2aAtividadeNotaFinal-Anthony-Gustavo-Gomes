<?php
$dbPath = 'livraria.db';

try {
    $db = new SQLite3($dbPath);
    
    $createTable = "CREATE TABLE IF NOT EXISTS livros (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        titulo TEXT NOT NULL,
        autor TEXT NOT NULL,
        ano_publicacao INTEGER
    )";
    $db->exec($createTable);
    
    $result = $db->query("SELECT * FROM livros");
    $livros = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $livros[] = $row;
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $stmt = $db->prepare("INSERT INTO livros (titulo, autor, ano_publicacao) VALUES (:titulo, :autor, :ano)");
        $stmt->bindValue(':titulo', $_POST['titulo'], SQLITE3_TEXT);
        $stmt->bindValue(':autor', $_POST['autor'], SQLITE3_TEXT);
        $stmt->bindValue(':ano', $_POST['ano_publicacao'], SQLITE3_TEXT);
        
        if($stmt->execute()) {
            header("Location: index.php");
            exit();
        }
    }
} catch(Exception $e) {
    echo "Erro: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Livraria - Lista de Livros</title>
    <style>
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 8px;
            margin-top: 20px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 10px;
        }
        th, td {
            padding: 15px;
            text-align: left;
            border: none;
        }
        th {
            background: rgba(0, 0, 0, 0.1);
            color: #333;
            font-weight: 600;
        }
        tr:hover {
            background: rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }
        .btn-excluir {
            background: rgba(255, 68, 68, 0.8);
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 6px;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 68, 68, 0.2);
        }
        .btn-excluir:hover {
            background: rgba(255, 68, 68, 0.9);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 68, 68, 0.2);
        }
        .btn-adicionar {
            background: rgba(76, 175, 80, 0.8);
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 8px;
            display: inline-block;
            margin-bottom: 20px;
            transition: all 0.3s ease;
            border: 1px solid rgba(76, 175, 80, 0.2);
        }
        .btn-adicionar:hover {
            background: rgba(76, 175, 80, 0.9);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(76, 175, 80, 0.2);
        }
    </style>
</head>
<body>
    <h1>Lista de Livros</h1>
    <a href="add_book.php" class="btn-adicionar">+ Adicionar Novo Livro</a>
    
    <table>
        <tr>
            <th>ID</th>
            <th>Título</th>
            <th>Autor</th>
            <th>Ano de Publicação</th>
            <th>Remover</th>
        </tr>
        <?php
        foreach ($livros as $livro) {
            echo "<tr>";
            echo "<td>" . $livro['id'] . "</td>";
            echo "<td>" . $livro['titulo'] . "</td>";
            echo "<td>" . $livro['autor'] . "</td>";
            echo "<td>" . ($livro['ano_publicacao'] ?? 'Não informado') . "</td>";
            echo "<td>
                    <a href='delete_book.php?id=" . $livro['id'] . "' 
                       class='btn-excluir' 
                       onclick='return confirm(\"Tem certeza que deseja excluir este livro?\")'>
                       Excluir
                    </a>
                  </td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>
</html>
