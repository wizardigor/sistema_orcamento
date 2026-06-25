<?php
require 'auth.php';
require 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Deleta os itens primeiro
    $stmt = $pdo->prepare("DELETE FROM orcamento_itens WHERE orcamento_id = ?");
    $stmt->execute([$id]);
    
    // Deleta o orçamento
    $stmt = $pdo->prepare("DELETE FROM orcamentos WHERE id = ?");
    $stmt->execute([$id]);
    
    header("Location: listar_orcamentos?status=excluido");
    exit;
}
?>