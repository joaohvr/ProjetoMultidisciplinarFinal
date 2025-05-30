<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION['cliente_id']) || empty($_SESSION['carrinho'])) {
    header("Location: index.php");
    exit;
}

$cliente_id = $_SESSION['cliente_id'];
$total = floatval($_POST['total'] ?? 0);
$endereco = trim($_POST['endereco'] ?? '');
$numero = trim($_POST['numero'] ?? '');
$metodo_pagamento = trim($_POST['metodo_pagamento'] ?? '');

if (empty($endereco) || empty($numero) || empty($metodo_pagamento)) {
    die("Por favor, preencha todos os campos obrigatórios.");
}

// Iniciar transação
$conexao->begin_transaction();

try {
    // Inserir o pedido
    $stmt = $conexao->prepare("
        INSERT INTO pedidos (cliente_id, endereco_entrega, numero, metodo_pagamento, total, data_pedido)
        VALUES (?, ?, ?, ?, ?, NOW())
    ");
    if (!$stmt) {
        throw new Exception("Erro ao preparar pedido: " . $conexao->error);
    }
    $stmt->bind_param("isssd", $cliente_id, $endereco, $numero, $metodo_pagamento, $total);
    if (!$stmt->execute()) {
        throw new Exception("Erro ao inserir pedido: " . $stmt->error);
    }

    $pedido_id = $stmt->insert_id;

    // Preparar inserção dos itens do pedido
    $stmt_item = $conexao->prepare("
        INSERT INTO itens_pedido (pedido_id, produto_id,preco_unitario)
        VALUES (?, ?, ?)
    ");
    if (!$stmt_item) {
        throw new Exception("Erro ao preparar itens do pedido: " . $conexao->error);
    }

    // Preparar atualização de estoque
    $stmt_estoque = $conexao->prepare("
        UPDATE produtos SET quantidade = quantidade - ? WHERE id = ? AND quantidade >= ?
    ");
    if (!$stmt_estoque) {
        throw new Exception("Erro ao preparar update do estoque: " . $conexao->error);
    }

    foreach ($_SESSION['carrinho'] as $item) {
        if (
            !isset($item['id'], $item['quantidade'], $item['preco']) ||
            !is_numeric($item['id']) || !is_numeric($item['quantidade']) || !is_numeric($item['preco'])
        ) {
            continue;
        }

        $produto_id = intval($item['id']);
        $quantidade = intval($item['quantidade']);
        $preco_unitario = floatval(str_replace(',', '.', $item['preco'])); // substitui vírgula por ponto, se houver

        // Inserir item do pedido
        $stmt_item->bind_param("iid", $pedido_id, $produto_id, $preco_unitario);
        if (!$stmt_item->execute()) {
            throw new Exception("Erro ao inserir item do pedido (Produto ID $produto_id): " . $stmt_item->error);
        }

        // Atualizar estoque
        $stmt_estoque->bind_param("iii", $quantidade, $produto_id, $quantidade);
        if (!$stmt_estoque->execute()) {
            throw new Exception("Erro ao atualizar estoque (Produto ID $produto_id): " . $stmt_estoque->error);
        }

        if ($stmt_estoque->affected_rows === 0) {
            throw new Exception("Estoque insuficiente para o produto ID $produto_id.");
        }
    }

    // Commit da transação
    $conexao->commit();

    // Limpar o carrinho
    unset($_SESSION['carrinho']);

    // Redirecionar para página de sucesso
    header("Location: minhas_compras.php?pedido_id=$pedido_id&ok=1");
    exit;

} catch (Exception $e) {
    // Rollback em caso de erro
    $conexao->rollback();
    die("Erro no processo de pagamento: " . $e->getMessage());
}
?>
