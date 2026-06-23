<?php
require 'auth.php';
require 'db.php';
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $pdo->beginTransaction();

        // 1. Obter e formatar número sequencial
        $tipo = $_POST['tipo_documento']; // 'ORC' ou 'PRO'

        $stmt_seq = $pdo->prepare("SELECT MAX(numero_sequencial) as max_num FROM orcamentos WHERE tipo_documento = ?");
        $stmt_seq->execute([$tipo]);
        $res = $stmt_seq->fetch();
        $proximo_numero = ($res['max_num'] ?? 0) + 1;

        // 2. Inserir Orçamento
        // No salvar_orcamento.php
        $sql_orc = "INSERT INTO orcamentos (tipo_documento, numero_sequencial, data_emissao, cliente_nome, cliente_documento, objeto_proposta, valor_total, observacoes, validade, prazo_entrega, condicao_pagamento, garantia) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_orc = $pdo->prepare($sql_orc);

        $stmt_orc->execute([
            $tipo,
            $proximo_numero,
            $_POST['data_emissao'],
            $_POST['cliente_nome'],
            $_POST['cliente_documento'],
            $_POST['objeto_proposta'] ?? '',
            $_POST['valor_total'],
            $_POST['observacoes'] ?? '' // Captura a observação
        ]);

        $stmt_orc->execute([
            $tipo,
            $proximo_numero,
            $_POST['data_emissao'],
            $_POST['cliente_nome'],
            $_POST['cliente_documento'],
            $_POST['objeto_proposta'],
            $_POST['valor_total'],
            $_POST['observacoes'],
            $_POST['validade'],
            $_POST['prazo_entrega'],
            $_POST['condicao_pagamento'],
            $_POST['garantia']
        ]);
        $orcamento_id = $pdo->lastInsertId();

        // 3. Inserir Itens
        $sql_item = "INSERT INTO orcamento_itens (orcamento_id, item_codigo, descricao, cor, quantidade, valor_unitario, subtotal) 
                     VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt_item = $pdo->prepare($sql_item);

        for ($i = 0; $i < count($_POST['codigo']); $i++) {
            $stmt_item->execute([
                $orcamento_id,
                $_POST['codigo'][$i],
                $_POST['descricao'][$i],
                $_POST['cor'][$i],
                $_POST['qtd'][$i],
                $_POST['valor_unit'][$i],
                ($_POST['qtd'][$i] * $_POST['valor_unit'][$i])
            ]);
        }

        $pdo->commit();

        header("Location: gerar_pdf?id=" . $orcamento_id);
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Erro ao salvar: " . $e->getMessage();
    }
}
