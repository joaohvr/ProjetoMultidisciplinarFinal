<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION['cliente_id'])) {
    header('Location: login_cliente.php');
    exit;
}

$id = $_SESSION['cliente_id'];

$result = $conexao->query("SELECT * FROM pedidos WHERE cliente_id = $id ORDER BY data_pedido DESC");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Minhas Compras</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h1>Minhas Compras</h1>

  <?php if ($result->num_rows > 0): ?>
    <table class="table table-striped">
      <thead>
        <tr><th>ID</th><th>Data</th><th>Total</th><th>Status</th></tr>
      </thead>
      <tbody>
        <?php while ($pedido = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($pedido['id']) ?></td>
            <td>
              <?= !empty($pedido['data_pedido']) 
                    ? date('d/m/Y H:i', strtotime($pedido['data_pedido'])) 
                    : 'Data não disponível' ?>
            </td>
            <td>R$ <?= number_format($pedido['total'], 2, ',', '.') ?></td>
            <td><?= htmlspecialchars($pedido['status']) ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>Você ainda não realizou nenhuma compra.</p>
  <?php endif; ?>

  <a href="index.php" class="btn btn-secondary mt-3">Voltar à Loja</a>
</div>
</body>
</html>
