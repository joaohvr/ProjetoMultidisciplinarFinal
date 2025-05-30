<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login_admin.php");
    exit;
}

$sql = "SELECT p.id, p.data_pedido, p.total, p.status, p.endereco_entrega, c.nome AS cliente_nome
        FROM pedidos p
        JOIN clientes c ON p.cliente_id = c.id
        WHERE p.status != 'Concluído'
        ORDER BY p.data_pedido DESC";

$result = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Pedidos Não Entregues - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand" href="#">Painel Admin</a>
            <div class="d-flex gap-2">
                <a href="cadastro.php" class="btn btn-outline-light">Cadastrar Produto</a>
                <a href="index.php" class="btn btn-outline-light">Voltar à Loja</a>
                <a href="admin_pedidos.php" class="btn btn-outline-light">Todos os Pedidos</a>
                <a href="admin_pedidos_nao_entregues.php" class="btn btn-light">Pedidos Pendentes</a>
                <a href="logout.php" class="btn btn-outline-light">Sair</a>
            </div>
        </div>
    </nav>

    <h1 class="mb-4">Pedidos Não Entregues</h1>

    <?php if ($result && $result->num_rows > 0): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Endereço Entrega</th>
                    <th>Data do Pedido</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($pedido = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($pedido['id']) ?></td>
                        <td><?= htmlspecialchars($pedido['cliente_nome']) ?></td>
                        <td><?= htmlspecialchars($pedido['endereco_entrega']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])) ?></td>
                        <td>R$ <?= number_format($pedido['total'], 2, ',', '.') ?></td>
                        <td><?= htmlspecialchars($pedido['status']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nenhum pedido pendente encontrado.</p>
    <?php endif; ?>

    <a href="admin_dashboard.php" class="btn btn-secondary mt-3">Voltar ao Painel</a>
</div>
</body>
</html>
