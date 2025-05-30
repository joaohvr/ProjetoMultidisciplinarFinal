<?php
session_start();
include 'conexao.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Catálogo de Produtos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
  <div class="container">
    <a class="navbar-brand" href="index.php">Loja Nova Era</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
      aria-controls="navbarNav" aria-expanded="false" aria-label="Alternar navegação">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">

        <li class="nav-item">
          <a class="nav-link" href="ver_carrinho.php">Carrinho 🛒</a>
        </li>

        <?php if (isset($_SESSION['cliente_id'])): ?>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Olá, <?= htmlspecialchars($_SESSION['cliente_nome']) ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
            <li><a class="dropdown-item" href="perfil_cliente.php">Meu Perfil</a></li>
            <li><a class="dropdown-item" href="minhas_compras.php">Minhas Compras</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="logout_cliente.php">Sair</a></li>
            </ul>
        </li>
        <?php else: ?>
        <li class="nav-item">
            <a class="nav-link" href="login_cliente.php">Login</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="cadastro_cliente.php">Cadastrar</a>
        </li>
        <?php endif; ?>

      </ul>
    </div>
  </div>
</nav>

<div class="container mt-5">
    <h1 class="mb-4">Produtos</h1>
    <div class="row">
        <?php
        $sql = "SELECT * FROM produtos";
        $result = $conexao->query($sql);

        if ($result->num_rows > 0) {
            while ($produto = $result->fetch_assoc()) {
                ?>
                <div class="col-12 col-sm-6 col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="<?= htmlspecialchars($produto['imagem']) ?>" class="card-img-top" alt="<?= htmlspecialchars($produto['nome']) ?>">
                        <div class="card-body text-center d-flex flex-column">
                            <h5 class="card-title"><?= htmlspecialchars($produto['nome']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($produto['descricao']) ?></p>

                            <form action="carrinho.php" method="post" class="d-flex justify-content-center align-items-center gap-2 mt-auto flex-wrap">
                                <input type="hidden" name="id" value="<?= $produto['id'] ?>">
                                <input type="hidden" name="nome" value="<?= htmlspecialchars($produto['nome']) ?>">
                                <input type="hidden" name="preco" value="<?= $produto['preco'] ?>">
                                <input type="hidden" name="imagem" value="<?= htmlspecialchars($produto['imagem']) ?>">

                                <button type="submit" class="btn btn-sm btn-success">Adicionar ao Carrinho</button>
                            </form>
                        </div>
                        <div class="card-footer text-center">
                            <strong>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></strong>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p>Nenhum produto encontrado.</p>";
        }

        $conexao->close();
        ?>
    </div>
</div>

<!-- Modal Bootstrap -->
<div class="modal fade" id="addCartModal" tabindex="-1" aria-labelledby="addCartModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body text-center">
        <h5 class="modal-title mb-3" id="addCartModalLabel">Item adicionado ao carrinho!</h5>
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Continuar Comprando</button>
        <a href="ver_carrinho.php" class="btn btn-success ms-2">Ver Carrinho</a>
      </div>
    </div>
  </div>
</div>

<?php if (!isset($_SESSION['cliente_id'])): ?>
<!-- Modal de Boas-Vindas -->
<div class="modal fade" id="boasVindasModal" tabindex="-1" aria-labelledby="boasVindasLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="boasVindasLabel">Bem-vindo à Loja Nova Era!</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body text-center">
        <p>Você já tem cadastro?</p>
        <div class="d-flex justify-content-center gap-3 mt-3">
          <a href="login_cliente.php" class="btn btn-primary">Sim, quero fazer login</a>
          <a href="cadastro_cliente.php" class="btn btn-outline-secondary">Não, quero me cadastrar</a>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  // Exibe o modal automaticamente ao carregar a página
  document.addEventListener('DOMContentLoaded', () => {
    const boasVindas = new bootstrap.Modal(document.getElementById('boasVindasModal'));
    boasVindas.show();
  });
</script>
<?php endif; ?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Verifica se o parâmetro ?add=sucesso está na URL e exibe o modal
document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('add') === 'sucesso') {
        const modal = new bootstrap.Modal(document.getElementById('addCartModal'));
        modal.show();

        // Remove o parâmetro da URL para não exibir novamente se atualizar
        history.replaceState(null, '', window.location.pathname);
    }
});
</script>

</body>
</html>