<?php
session_start();
include 'conexao.php';

// Verifica se admin está logado
if (!isset($_SESSION['admin_id'])) {
    header("Location: login_admin.php");
    exit;
}

$id = $_POST['id'] ?? null;
if ($id) {
    $stmt = $conexao->prepare("DELETE FROM produtos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: admin_produtos.php");
exit;
