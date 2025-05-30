<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION['cliente_id']) || empty($_SESSION['carrinho'])) {
    header("Location: index.php");
    exit;
}

$cliente_id = $_SESSION['cliente_id'];

// Buscar dados do cliente para mostrar o nome
$sql_cliente = $conexao->prepare("SELECT nome FROM clientes WHERE id = ?");
$sql_cliente->bind_param("i", $cliente_id);
$sql_cliente->execute();
$result_cliente = $sql_cliente->get_result();
$cliente = $result_cliente->fetch_assoc();

$total = 0;
foreach ($_SESSION['carrinho'] as $item) {
    $total += $item['preco'] * $item['quantidade'];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Finalizar Compra</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-5">
    <h1>Finalizar Compra</h1>

    <form action="pagamento.php" method="post">
        <div class="mb-3">
            <label class="form-label">Nome do Cliente</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($cliente['nome']) ?>" readonly />
        </div>

        <div class="mb-3">
            <label for="endereco" class="form-label">Endereço de Entrega</label>
            <input type="text" id="endereco" name="endereco" class="form-control" placeholder="Rua, Avenida, etc" required />
        </div>

        <div class="mb-3">
            <label for="numero" class="form-label">Número da Casa</label>
            <input type="text" id="numero" name="numero" class="form-control" placeholder="Número da casa" required />
        </div>

        <div class="mb-3">
            <label for="metodo_pagamento" class="form-label">Método de Pagamento</label>
            <select id="metodo_pagamento" name="metodo_pagamento" class="form-select" required>
                <option value="">Selecione</option>
                <option value="PIX">PIX</option>
                <option value="Cartão">Cartão</option>
            </select>
        </div>

        <h4>Resumo do Pedido</h4>
        <ul class="list-group mb-3">
            <?php foreach ($_SESSION['carrinho'] as $item): 
                $subtotal = $item['preco'] * $item['quantidade'];
            ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?= htmlspecialchars($item['nome']) ?> x <?= $item['quantidade'] ?>
                    <span>R$ <?= number_format($subtotal, 2, ',', '.') ?></span>
                </li>
            <?php endforeach; ?>
            <li class="list-group-item d-flex justify-content-between">
                <strong>Total:</strong>
                <strong>R$ <?= number_format($total, 2, ',', '.') ?></strong>
            </li>
        </ul>

        <input type="hidden" name="total" value="<?= $total ?>">

        <button type="submit" class="btn btn-success">Confirmar e Pagar</button>
        <a href="ver_carrinho.php" class="btn btn-secondary ms-2">Voltar</a>
    </form>
</div>
</body>
</html>
