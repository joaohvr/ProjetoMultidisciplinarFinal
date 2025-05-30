<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION['cliente_id'])) {
    header('Location: login_cliente.php');
    exit;
}

$id = $_SESSION['cliente_id'];
$result = $conexao->query("SELECT * FROM clientes WHERE id = $id");
$cliente = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Meu Perfil</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h1>Meu Perfil</h1>
  <p><strong>Nome:</strong> <?= htmlspecialchars($cliente['nome']) ?></p>
  <p><strong>Email:</strong> <?= htmlspecialchars($cliente['email']) ?></p>
  <a href="index.php" class="btn btn-secondary">Voltar à Loja</a>
</div>
</body>
</html>
