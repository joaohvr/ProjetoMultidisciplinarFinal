<?php
session_start();
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (empty($username) || empty($password)) {
        $erro = "Por favor, preencha todos os campos.";
    } else {
        $stmt = $conexao->prepare("SELECT id, password FROM admins WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $admin = $result->fetch_assoc();
            if ($password == $admin['password']) {
                $_SESSION['admin_id'] = $admin['id'];
                header("Location: admin_pedidos.php");
                exit;
            } else {
                $erro = "Usuário ou senha inválidos.";
            }
        } else {
            $erro = "Usuário ou senha inválidos.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Login Admin - Loja Nova Era</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

</head>
<body class="bg-light d-flex align-items-center" style="height: 100vh;">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title mb-4 text-center">Login Administrador</h3>

                    <?php if (!empty($erro)) : ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo htmlspecialchars($erro); ?>
                        </div>
                    <?php elseif (isset($_GET['erro'])) : ?>
                        <div class="alert alert-danger" role="alert">
                            Usuário ou senha inválidos.
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="login_admin.php" novalidate>
                        <div class="mb-3">
                            <label for="username" class="form-label">Usuário</label>
                            <input type="text" class="form-control" id="username" name="username" required autofocus />
                            <div class="invalid-feedback">
                                Por favor, insira seu usuário.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Senha</label>
                            <input type="password" class="form-control" id="password" name="password" required />
                            <div class="invalid-feedback">
                                Por favor, insira sua senha.
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Entrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS Bundle (inclui Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@54.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Exemplo simples para validar os campos com Bootstrap 5
(() => {
  'use strict'
  const forms = document.querySelectorAll('form')

  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }
      form.classList.add('was-validated')
    }, false)
  })
})()
</script>

</body>
</html>
