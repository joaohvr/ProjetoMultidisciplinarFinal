<?php
session_start();
include 'conexao.php';

$erro = '';
$sucesso = '';
// Se tinha carrinho salvo antes do login
if (isset($_SESSION['carrinho_temporario'])) {
    $_SESSION['carrinho'] = $_SESSION['carrinho_temporario'];
    unset($_SESSION['carrinho_temporario']);
}

// Redireciona para onde estava tentando ir
if (isset($_SESSION['redirecionar_para'])) {
    header("Location: " . $_SESSION['redirecionar_para']);
    unset($_SESSION['redirecionar_para']);
    exit;
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $confirma_senha = $_POST['confirma_senha'] ?? '';
    $endereco       = $_POST['endereco'] ?? '';
    $cep            = $_POST['cep'] ?? '';
    $numeroTel      = $_POST['numeroTel'] ?? '';

    if (!$nome || !$email || !$senha || !$confirma_senha) {
        $erro = 'Por favor, preencha todos os campos.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'Email inválido.';
    } elseif ($senha !== $confirma_senha) {
        $erro = 'As senhas não conferem.';
    } else {
        // Verifica se email já existe
        $stmt = $conexao->prepare("SELECT id FROM clientes WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $erro = 'Email já cadastrado.';
        } else {
            // Insere cliente com senha hash
            $hash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $conexao->prepare("INSERT INTO clientes (nome, email, senha,endereco,CEP,numeroTel) VALUES (?, ?, ?,?,?,?)");
            $stmt->bind_param("ssssss", $nome, $email, $hash,$endereco,$cep,$numeroTel);
            if ($stmt->execute()) {
                $sucesso = 'Cadastro realizado com sucesso! Você já pode fazer login.';
            } else {
                $erro = 'Erro ao cadastrar, tente novamente.';
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8" />
<title>Cadastro Cliente</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-5" style="max-width: 450px;">
    <h2 class="mb-4 text-center">Cadastro de Cliente</h2>

    <?php if ($erro): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
    <?php elseif ($sucesso): ?>
        <div class="alert alert-success"><?= htmlspecialchars($sucesso) ?></div>
    <?php endif; ?>

    <form method="post" novalidate>
        <div class="mb-3">
            <label for="nome" class="form-label">Nome Completo</label>
            <input type="text" id="nome" name="nome" class="form-control" required value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" id="email" name="email" class="form-control" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label for="enderco" class="form-label">Endereco</label>
            <input type="endereco" id="endereco" name="endereco" class="form-control" required value="<?= htmlspecialchars($_POST['endereco'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label for="numeroTel" class="form-label">Telefone</label>
            <input type="numeroTel" id="numeroTel" name="numeroTel" class="form-control" required value="<?= htmlspecialchars($_POST['numeroTel'] ?? '') ?>">
        </div>
         <div class="mb-3">
            <label for="cep" class="form-label">CEP</label>
            <input type="cep" id="cep" name="cep" class="form-control" required value="<?= htmlspecialchars($_POST['cep'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label for="senha" class="form-label">Senha</label>
            <input type="password" id="senha" name="senha" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="confirma_senha" class="form-label">Confirme a Senha</label>
            <input type="password" id="confirma_senha" name="confirma_senha" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
    </form>

    <p class="mt-3 text-center">
        Já tem conta? <a href="login_cliente.php">Faça login aqui</a>.
    </p>
</div>
</body>
</html>
