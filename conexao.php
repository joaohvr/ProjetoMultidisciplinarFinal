<?php
$host = 'localhost:40400';
$usuario = 'root';
$senha = '';
$banco = 'loja';

$conexao = new mysqli($host, $usuario, $senha, $banco);

if ($conexao->connect_error) {
    die("Erro na conexão: " . $conexao->connect_error);
}
?>
