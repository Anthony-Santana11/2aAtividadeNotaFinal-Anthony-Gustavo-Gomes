<?php
require_once 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? 0;

    if ($id > 0) {
        $db = new Database();
        $db->deletarTarefa($id);
    }
}

header('Location: index.php');
exit;
?>