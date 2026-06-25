<?php
require 'auth.php';
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    try {
        $pdo->beginTransaction();

        // 1. Atualiza o cabeçalho
        $sql = "UPDATE orcamentos SET cliente_nome = ?, cliente_documento = ?, objeto_proposta = ?, 
                valor_total = ?, observacoes = ?, validade = ?, prazo_entrega = ?, 
                condicao_pagamento = ?, garantia = ? WHERE id = ?";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $_POST['cliente_nome'], $_POST['cliente_documento'], $_POST['objeto_proposta'],
            $_POST['valor_total'], $_POST['observacoes'], $_POST['validade'],
            $_POST['prazo_entrega'], $_POST['condicao_pagamento'], $_POST['garantia'], $id
        ]);

        // 2. Remove itens antigos
        $pdo->prepare("DELETE FROM orcamento_itens WHERE orcamento_id = ?")->execute([$id]);

        // 3. Insere os itens novos (mesma lógica do salvar_orcamento)
        $sql_item = "INSERT INTO orcamento_itens (orcamento_id, item_codigo, descricao, cor, quantidade, valor_unitario, subtotal) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt_item = $pdo->prepare($sql_item);

        for ($i = 0; $i < count($_POST['codigo']); $i++) {
            $stmt_item->execute([
                $id, $_POST['codigo'][$i], $_POST['descricao'][$i], $_POST['cor'][$i],
                $_POST['qtd'][$i], $_POST['valor_unit'][$i], ($_POST['qtd'][$i] * $_POST['valor_unit'][$i])
            ]);
        }

        $pdo->commit();
        header("Location: listar_orcamento?status=sucesso");
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Erro ao atualizar: " . $e->getMessage();
    }
}