<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login_admin.php");
    exit;
}

$result = $conexao->query("SELECT * FROM produtos ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Admin - Produtos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">

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

<div class="container">
    <h1 class="mb-4 text-center">Produtos Cadastrados</h1>

    <table class="table table-striped table-bordered align-middle text-center">
        <thead class="table-primary">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Preço</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody>
            <?php while($produto = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $produto['id'] ?></td>
                <td><?= htmlspecialchars($produto['nome']) ?></td>
                <td>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>
                <td>
                    <form method="post" action="deletar_produto.php" onsubmit="return confirm('Confirma deletar este produto?');" class="d-inline">
                        <input type="hidden" name="id" value="<?= $produto['id'] ?>" />
                        <button type="submit" class="btn btn-danger btn-sm">Deletar</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
