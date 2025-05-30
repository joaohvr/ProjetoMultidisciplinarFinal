<?php
session_start();
include 'conexao.php';

// Verifica se o admin está logado, para permitir alterar o status (opcional, mas recomendado)
if (!isset($_SESSION['admin_id'])) {
    header('Location: login_admin.php');
    exit;
}

// Verifica se os dados foram enviados via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pedido_id = $_POST['pedido_id'] ?? null;
    $novo_status = $_POST['status'] ?? null;

    if ($pedido_id && $novo_status) {
        // Atualiza o status no banco
        $stmt = $conexao->prepare("UPDATE pedidos SET status = ? WHERE id = ?");
        $stmt->bind_param('si', $novo_status, $pedido_id);

        if ($stmt->execute()) {
            $_SESSION['msg'] = "Status do pedido atualizado com sucesso.";
        } else {
            $_SESSION['msg'] = "Erro ao atualizar status.";
        }
    } else {
        $_SESSION['msg'] = "Dados inválidos.";
    }
} else {
    $_SESSION['msg'] = "Método inválido.";
}

header('Location: listar_pedidos.php'); // Ajuste para a página que lista pedidos/admin
exit;
