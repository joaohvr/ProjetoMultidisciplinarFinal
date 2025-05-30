<?php
    session_start();

    $id = $_POST['id'] ?? null;

    if ($id && isset($_SESSION['carrinho'][$id])) {
        unset($_SESSION['carrinho'][$id]);
    }

    header("Location: ver_carrinho.php");
exit;