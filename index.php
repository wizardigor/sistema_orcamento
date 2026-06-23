<?php
// index.php
session_start();

if (isset($_SESSION['usuario_id'])) {
    // Se já estiver logado, vai direto para o painel
    header("Location: dashboard");
} else {
    // Se não, vai para o login
    header("Location: login");
}
exit;
?>