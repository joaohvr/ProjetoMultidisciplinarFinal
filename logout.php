<?php
    session_start();
    session_unset();      // Remove todas as variáveis da sessão
    session_destroy();    // Encerra a sessão

    // Redireciona para a página inicial
    header("Location: index.php");
exit;
