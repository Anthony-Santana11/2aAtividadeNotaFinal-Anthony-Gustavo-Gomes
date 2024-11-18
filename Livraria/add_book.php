<?php
// Processamento do formulário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Conecta ao banco SQLite
        $dbPath = 'livraria.db';
        $conexao = new SQLite3($dbPath);

        // Coleta os dados do formulário
        $titulo = trim($_POST['titulo']);
        $autor = trim($_POST['autor']);
        $ano_publicacao = intval($_POST['ano_publicacao']);

        // Validação básica
        if (empty($titulo) || empty($autor) || empty($ano_publicacao)) {
            throw new Exception("Por favor, preencha todos os campos corretamente.");
        }

        // Prepara e executa a query
        $stmt = $conexao->prepare('INSERT INTO livros (titulo, autor, ano_publicacao) VALUES (:titulo, :autor, :ano_publicacao)');
        $stmt->bindValue(':titulo', $titulo, SQLITE3_TEXT);
        $stmt->bindValue(':autor', $autor, SQLITE3_TEXT);
        $stmt->bindValue(':ano_publicacao', $ano_publicacao, SQLITE3_INTEGER);

        if ($stmt->execute()) {
            echo "<script>
                    alert('Livro adicionado com sucesso!');
                    window.location.href = 'index.php';
                  </script>";
            exit();
        } else {
            throw new Exception("Erro ao adicionar o livro.");
        }
    } catch (Exception $e) {
        $erro = "Erro: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Livro</title>
    <style>
        .form-container {
            max-width: 500px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .btn-submit {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-submit:hover {
            background-color: #45a049;
        }
        .btn-voltar {
            display: inline-block;
            margin-top: 10px;
            color: #666;
            text-decoration: none;
        }
        .erro {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Adicionar Novo Livro</h2>
        
        <?php if (isset($erro)): ?>
            <div class="erro"><?php echo $erro; ?></div>
        <?php endif; ?>

        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-group">
                <label for="titulo">Título:</label>
                <input type="text" id="titulo" name="titulo" 
                       value="<?php echo isset($_POST['titulo']) ? htmlspecialchars($_POST['titulo']) : ''; ?>" 
                       required>
            </div>

            <div class="form-group">
                <label for="autor">Autor:</label>
                <input type="text" id="autor" name="autor" 
                       value="<?php echo isset($_POST['autor']) ? htmlspecialchars($_POST['autor']) : ''; ?>" 
                       required>
            </div>

            <div class="form-group">
                <label for="ano_publicacao">Ano de Publicação:</label>
                <input type="number" id="ano_publicacao" name="ano_publicacao" min="1" max="<?php echo date('Y'); ?>"
                       value="<?php echo isset($_POST['ano_publicacao']) ? htmlspecialchars($_POST['ano_publicacao']) : ''; ?>" 
                       required>
            </div>

            <button type="submit" class="btn-submit">Adicionar Livro</button>
        </form>
    </div>
</body>
</html>
