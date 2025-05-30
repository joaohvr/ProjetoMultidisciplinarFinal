<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Admin - Cadastrar Produto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>



<div class="container mt-3">
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
    <h1>Cadastrar Produto</h1>

    <form action="upload.php" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" id="nome" name="nome" class="form-control" required />
        </div>

        <div class="mb-3">
            <label for="preco" class="form-label">Preço (R$)</label>
            <input type="number" step="0.01" min="0" id="preco" name="preco" class="form-control" required />
        </div>

        <div class="mb-3">
            <label for="quantidade" class="form-label">Quantidade</label>
            <input type="number" min="0" id="quantidade" name="quantidade" class="form-control" required />
        </div>

        <div class="mb-3">
            <label class="form-label">Imagem do Produto</label>
            <input type="file" name="imagem" accept="image/*" class="form-control" />
            <small class="form-text text-muted">Ou informe o link da imagem abaixo:</small>
            <input type="url" name="link_imagem" class="form-control mt-1" placeholder="https://exemplo.com/imagem.jpg" />
        </div>

        <button type="submit" class="btn btn-success">Cadastrar Produto</button>
        <a href="admin_dashboard.php" class="btn btn-secondary ms-2">Voltar ao Painel</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
