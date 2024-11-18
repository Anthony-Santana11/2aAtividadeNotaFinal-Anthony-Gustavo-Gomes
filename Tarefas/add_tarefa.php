<?php
require_once 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descricao = $_POST['descricao'] ?? '';
    $data_vencimento = $_POST['data_vencimento'] ?? '';

    if (!empty($descricao) && !empty($data_vencimento)) {
        $db = new Database();
        $db->adicionarTarefa($descricao, $data_vencimento);
    }
}

header('Location: index.php');
exit;
?>