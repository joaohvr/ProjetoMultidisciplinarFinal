<?php
session_start();

// Remove apenas o carrinho
unset($_SESSION['carrinho']);

// Redireciona para ver_carrinho.php
header("Location: ver_carrinho.php");
exit;
