<?php
session_start();
include 'conexao.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if (!$email || !$senha) {
        $erro = 'Preencha email e senha.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'Email inválido.';
    } else {
        $stmt = $conexao->prepare("SELECT id, senha, nome FROM clientes WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($usuario = $result->fetch_assoc()) {
            if (password_verify($senha, $usuario['senha'])) {
                // Login válido
                $_SESSION['cliente_id'] = $usuario['id'];
                $_SESSION['cliente_nome'] = $usuario['nome'];

                header("Location: index.php"); // redireciona para loja
                exit;
            } else {
                $erro = 'Senha incorreta.';
            }
        } else {
            $erro = 'Usuário não encontrado.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8" />
<title>Login Cliente</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-5" style="max-width: 400px;">
    <h2 class="mb-4 text-center">Login Cliente</h2>

    <?php if ($erro): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <form method="post" novalidate>
        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" id="email" name="email" class="form-control" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label for="senha" class="form-label">Senha</label>
            <input type="password" id="senha" name="senha" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Entrar</button>
    </form>

    <p class="mt-3 text-center">
        Não tem conta? <a href="cadastro_cliente.php">Cadastre-se aqui</a>.
    </p>
</div>
</body>
</html>
