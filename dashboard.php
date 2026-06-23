<?php 
require 'auth.php'; // Garante que apenas usuários logados acessem
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Orçamento - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <span class="navbar-brand">Sistema de Orçamento</span>
        <a href="logout" class="btn btn-outline-danger btn-sm">Sair</a>
    </div>
</nav>

<div class="container text-center">
    <h2 class="mb-4">Bem-vindo, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>!</h2>
    
    <div class="row justify-content-center mt-5">
        <div class="col-md-4">
            <a href="novo_orcamento" class="btn btn-primary btn-lg w-100 p-4 shadow">
                ➕ Novo Orçamento
            </a>
        </div>
        
        <div class="col-md-4">
            <a href="listar_orcamentos" class="btn btn-success btn-lg w-100 p-4 shadow">
                📋 Consultar Orçamentos
            </a>
        </div>
    </div>
</div>

</body>
</html>