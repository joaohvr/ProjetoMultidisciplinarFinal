<?php
session_start();

$carrinho = $_SESSION['carrinho'] ?? [];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Seu Carrinho</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-5" style="max-width: 700px;">
    <h1 class="text-center mb-4">Seu Carrinho 🛒</h1>

    <?php if (empty($carrinho)): ?>
        <p class="text-center">Seu carrinho está vazio.</p>
        <div class="text-center">
            <a href="index.php" class="btn btn-primary">Voltar ao catálogo</a>
        </div>
    <?php else: ?>
        <table class="table table-bordered align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th>Imagem</th>
                    <th>Produto</th>
                    <th>Preço Unitário</th>
                    <th>Quantidade</th>
                    <th>Subtotal</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach ($carrinho as $id => $item):
                    $subtotal = $item['preco'] * $item['quantidade'];
                    $total += $subtotal;
                ?>
                <tr>
                    <td>
                        <img src="<?= htmlspecialchars($item['imagem'] ?? 'imagens/default.png') ?>" alt="<?= htmlspecialchars($item['nome']) ?>" style="width: 60px; height: 60px; object-fit: contain;">
                    </td>
                    <td><?= htmlspecialchars($item['nome']) ?></td>
                    <td>R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
                    <td><?= $item['quantidade'] ?></td>
                    <td>R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
                    <td>
                        <!-- Botão para abrir modal -->
                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmRemoveModal<?= $id ?>">
                          Remover
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="confirmRemoveModal<?= $id ?>" tabindex="-1" aria-labelledby="modalLabel<?= $id ?>" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="modalLabel<?= $id ?>">Confirmar remoção</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                              </div>
                              <div class="modal-body">
                                Deseja remover este item do carrinho?
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <form action="remover_item.php" method="post" class="d-inline">
                                  <input type="hidden" name="id" value="<?= $id ?>">
                                  <button type="submit" class="btn btn-danger">Remover</button>
                                </form>
                              </div>
                            </div>
                          </div>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="4" class="text-end"><strong>Total</strong></td>
                    <td><strong>R$ <?= number_format($total, 2, ',', '.') ?></strong></td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        <div class="text-center">
            <a href="index.php" class="btn btn-secondary">Continuar Comprando</a>
            <a href="finalizar_compra.php" class="btn btn-success me-2">Finalizar Compra</a>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
