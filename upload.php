<?php
include 'conexao.php';

$nome = $_POST['nome'];
$preco = $_POST['preco'];
$quantidade = $_POST['quantidade'];

$pasta = "imagens/";
$caminhoImagem = '';

if (!empty($_FILES['imagem']['name'])) {
    $imagem = $_FILES['imagem'];
    
    $nomeImagem = basename($imagem['name']);
    $nomeImagem = preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $nomeImagem);

    $caminhoCompleto = $pasta . $nomeImagem;

    if (move_uploaded_file($imagem['tmp_name'], $caminhoCompleto)) {
        $caminhoImagem = $caminhoCompleto;
    } else {
        die("Erro no upload da imagem.");
    }
} elseif (!empty($_POST['link_imagem'])) {
    $caminhoImagem = $_POST['link_imagem'];
} else {
    $caminhoImagem = '';
}

$sql = "INSERT INTO produtos (nome, preco, quantidade, imagem) VALUES (?, ?, ?, ?)";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("sdis", $nome, $preco, $quantidade, $caminhoImagem);

if ($stmt->execute()) {
    header("Location: admin_produtos.php");

} else {
    echo "Erro ao cadastrar produto: " . $stmt->error;
}
