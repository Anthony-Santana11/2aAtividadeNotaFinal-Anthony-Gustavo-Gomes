<?php
$dbPath = 'livraria.db';

try {
    // Conecta ao banco SQLite
    $db = new SQLite3($dbPath);
    
    if(isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
        
        // Verifica se o livro existe
        $checkStmt = $db->prepare('SELECT id FROM livros WHERE id = :id');
        $checkStmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $result = $checkStmt->execute();
        
        if($result->fetchArray()) {
            // Prepara e executa a query de delete
            $stmt = $db->prepare('DELETE FROM livros WHERE id = :id');
            $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
            
            if($stmt->execute()) {
                header('Location: index.php?message=success&action=delete');
                exit();
            }
        }
        header('Location: index.php?message=error&action=delete');
        exit();
    }
    header('Location: index.php?message=invalid_id');
    exit();

} catch (Exception $e) {
    error_log("Erro ao excluir livro: " . $e->getMessage());
    header('Location: index.php?message=error&action=delete');
    exit();
}
?>
