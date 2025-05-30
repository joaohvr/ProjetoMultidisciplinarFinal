<?php
session_start();

// Inicializa carrinho se não existir
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

// Recebe dados do formulário
$id = $_POST['id'] ?? null;
$nome = $_POST['nome'] ?? null;
$preco = $_POST['preco'] ?? 0;
$quantidade = isset($_POST['quantidade']) ? (int)$_POST['quantidade'] : 1;
$imagem = $_POST['imagem'] ?? '';

if ($id && $nome) {
    // Se produto já existe no carrinho, soma quantidade
    if (isset($_SESSION['carrinho'][$id])) {
        $_SESSION['carrinho'][$id]['quantidade'] += $quantidade;
    } else {
        $_SESSION['carrinho'][$id] = [
            'nome' => $nome,
            'preco' => $preco,
            'quantidade' => $quantidade,
            'imagem' => $imagem
        ];
    }
}

// Redireciona para o catálogo com parâmetro para mostrar modal
header("Location: index.php?add=sucesso");
exit;
