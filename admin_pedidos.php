<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login_admin.php");
    exit;
}
// Processa alteração de status, se enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pedido_id = $_POST['pedido_id'] ?? null;
    $novo_status = $_POST['status'] ?? null;

    if ($pedido_id && $novo_status) {
        $stmt = $conexao->prepare("UPDATE pedidos SET status = ? WHERE id = ?");
        $stmt->bind_param('si', $novo_status, $pedido_id);
        if ($stmt->execute()) {
            $_SESSION['msg'] = "Status do pedido #$pedido_id atualizado com sucesso.";
        } else {
            $_SESSION['msg'] = "Erro ao atualizar status do pedido #$pedido_id.";
        }
    } else {
        $_SESSION['msg'] = "Dados inválidos para atualização.";
    }
    header('Location: admin_pedidos.php');
    exit;
}

// Busca os pedidos para listar
$result = $conexao->query("SELECT * FROM pedidos ORDER BY data_pedido DESC");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Admin - Gerenciar Pedidos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">


  <!-- NAVBAR PADRÃO DO ADMIN -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
        <a class="navbar-brand" href="#">Painel Admin</a>
        <div class="d-flex gap-2">
            <a href="cadastro.php" class="btn btn-outline-light">Cadastrar Novo Produto</a>
            <a href="index.php" class="btn btn-outline-light">Voltar à Loja</a>
            <a href="admin_pedidos.php" class="btn btn-outline-light">Todos os Pedidos</a>
            <a href="admin_pedidos_nao_entregues.php" class="btn btn-outline-light">Pedidos Pendentes</a>
            <a href="logout.php" class="btn btn-outline-light">Sair</a>
        </div>
    </div>
</nav>

    <h1>Gerenciar Pedidos</h1>


    <?php if (isset($_SESSION['msg'])): ?>
        <div class="alert alert-info"><?= $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
    <?php endif; ?>

    <?php if ($result->num_rows > 0): ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente ID</th>
                <th>Data</th>
                <th>Total</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($pedido = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $pedido['id'] ?></td>
                <td><?= $pedido['cliente_id'] ?></td>
                <td><?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])) ?></td>
                <td>R$ <?= number_format($pedido['total'], 2, ',', '.') ?></td>
                <td>
                    <form method="post" class="d-flex gap-2 align-items-center">
                        <input type="hidden" name="pedido_id" value="<?= $pedido['id'] ?>">
                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="Pendente" <?= $pedido['status'] === 'Pendente' ? 'selected' : '' ?>>Pendente</option>
                            <option value="Processando" <?= $pedido['status'] === 'Processando' ? 'selected' : '' ?>>Processando</option>
                            <option value="Enviado" <?= $pedido['status'] === 'Enviado' ? 'selected' : '' ?>>Enviado</option>
                            <option value="Concluído" <?= $pedido['status'] === 'Concluído' ? 'selected' : '' ?>>Concluído</option>
                            <option value="Cancelado" <?= $pedido['status'] === 'Cancelado' ? 'selected' : '' ?>>Cancelado</option>
                        </select>
                    </form>
                </td>
                <td>
                    <a href="detalhes_pedido.php?id=<?= $pedido['id'] ?>" class="btn btn-sm btn-primary">Detalhes</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p>Nenhum pedido encontrado.</p>
    <?php endif; ?>

    <a href="admin_dashboard.php" class="btn btn-secondary mt-3">Voltar ao Painel</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
