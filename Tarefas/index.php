<?php
require_once 'database.php';
$db = new Database();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador de Tarefas</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .nova-tarefa {
            margin-bottom: 30px;
        }

        .nova-tarefa form {
            display: flex;
            gap: 10px;
        }

        input[type="text"],
        input[type="date"] {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        input[type="text"] {
            flex: 1;
        }

        button {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            color: white;
        }

        button[type="submit"] {
            background-color: #4CAF50;
        }

        .botao-concluir {
            background-color: #2196F3;
        }

        .botao-deletar {
            background-color: #f44336;
        }

        .lista-tarefas {
            margin-bottom: 30px;
        }

        .tarefa-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .tarefa-texto {
            flex: 1;
        }

        .tarefa-texto small {
            color: #666;
            margin-left: 10px;
        }

        .botoes {
            display: flex;
            gap: 5px;
        }

        .concluida .tarefa-texto {
            text-decoration: line-through;
            color: #888;
        }

        button:hover {
            opacity: 0.9;
        }

        .vencida {
            background-color: #ffe6e6;
        }

        .hoje {
            background-color: #fff3cd;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gerenciador de Tarefas</h1>

        
        <div class="nova-tarefa">
            <form action="add_tarefa.php" method="POST">
                <input type="text" name="descricao" placeholder="Descrição da tarefa" required>
                <input type="date" name="data_vencimento" required>
                <button type="submit">Adicionar Tarefa</button>
            </form>
        </div>

        
        <h2>Tarefas Pendentes</h2>
        <div class="lista-tarefas">
            <?php
            $tarefas = $db->getTarefasPendentes();
            while ($tarefa = $tarefas->fetchArray(SQLITE3_ASSOC)) {
                $hoje = date('Y-m-d');
                $classe = '';
                if ($tarefa['data_vencimento'] < $hoje) {
                    $classe = 'vencida';
                } elseif ($tarefa['data_vencimento'] == $hoje) {
                    $classe = 'hoje';
                }
                ?>
                <div class="tarefa-item <?php echo $classe; ?>">
                    <span class="tarefa-texto">
                        <?php echo htmlspecialchars($tarefa['descricao']); ?>
                        <small>(Vence em: <?php echo date('d/m/Y', strtotime($tarefa['data_vencimento'])); ?>)</small>
                    </span>
                    <div class="botoes">
                        <form action="update_tarefa.php" method="POST" style="display: inline;">
                            <input type="hidden" name="id" value="<?php echo $tarefa['id']; ?>">
                            <button type="submit" class="botao-concluir">Concluir</button>
                        </form>
                        <form action="delete_tarefa.php" method="POST" style="display: inline;">
                            <input type="hidden" name="id" value="<?php echo $tarefa['id']; ?>">
                            <button type="submit" class="botao-deletar">Deletar</button>
                        </form>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>

        
        <h2>Tarefas Concluídas</h2>
        <div class="lista-tarefas concluidas">
            <?php
            $tarefas = $db->getTarefasConcluidas();
            while ($tarefa = $tarefas->fetchArray(SQLITE3_ASSOC)) {
                ?>
                <div class="tarefa-item concluida">
                    <span class="tarefa-texto">
                        <?php echo htmlspecialchars($tarefa['descricao']); ?>
                        <small>(Venceu em: <?php echo date('d/m/Y', strtotime($tarefa['data_vencimento'])); ?>)</small>
                    </span>
                    <div class="botoes">
                        <form action="delete_tarefa.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo $tarefa['id']; ?>">
                            <button type="submit" class="botao-deletar">Deletar</button>
                        </form>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</body>
</html> 