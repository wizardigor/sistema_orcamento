<?php
require 'auth.php';
require 'db.php';
require 'vendor/autoload.php';

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

use Dompdf\Dompdf;
use Dompdf\Options;

// 1. Busca dados do orçamento
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM orcamentos WHERE id = ?");
$stmt->execute([$id]);
$orc = $stmt->fetch();

// 2. Busca itens do orçamento
$stmt_itens = $pdo->prepare("SELECT * FROM orcamento_itens WHERE orcamento_id = ?");
$stmt_itens->execute([$id]);
$itens = $stmt_itens->fetchAll();

// Processamento da Logo
$path = $_SERVER['DOCUMENT_ROOT'] . '/orcamento/logo.png';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$base64 = 'data:image/' . $type . ';base64,' . str_replace(["\r", "\n"], '', base64_encode($data));

$titulo_documento = ($orc['tipo_documento'] == 'ORC') ? 'ORÇAMENTO' : 'PROPOSTA';

// Cálculo dinâmico de CPF ou CNPJ
$doc_limpo = preg_replace('/[^0-9]/', '', $orc['cliente_documento']);
$label_doc = (strlen($doc_limpo) <= 11) ? 'CPF:' : 'CNPJ:';

// 3. Montagem do HTML
$html = "
<html>
<head>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header-table { width: 100%; border: none; }
        .logo { width: 150px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .items-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .items-table th, .items-table td { border: 1px solid #000; padding: 8px; }
        .adicionais-table { width: 100%; border-collapse: collapse; margin-top: 30px; }
        .adicionais-table th, .adicionais-table td { border: 1px solid #000; padding: 5px; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; border-top: 1px solid #000; padding-top: 5px; }
    </style>
</head>
<body>
    <table class='header-table'>
        <tr><td class='text-right'><img src='{$base64}' class='logo'></td></tr>
        <tr><td class='text-center'><h1>PROPOSTA COMERCIAL/ORÇAMENTO</h1></td></tr>
        <tr>
            <td class='text-right'>
                <p style='font-size: 14px;'>
                    {$titulo_documento} Nº {$orc['tipo_documento']}-2026-" . str_pad($orc['numero_sequencial'], 3, '0', STR_PAD_LEFT) . "<br>
                    Data: " . date('d/m/Y', strtotime($orc['data_emissao'])) . "
                </p>
            </td>
        </tr>
    </table>

    <div style='margin-top: 20px;'><strong>Objeto da Proposta:</strong><p>{$orc['objeto_proposta']}</p></div>

    <table class='items-table'>
        <thead><tr><th>Código</th><th>Descrição</th><th>Cor</th><th>Qtd.</th><th>Vl. Unit.</th><th>Subtotal</th></tr></thead>
        <tbody>";
foreach ($itens as $i) {
    $valor_unit = number_format($i['valor_unitario'], 2, ',', '.');
    $subtotal = number_format($i['subtotal'], 2, ',', '.');
    $html .= "<tr><td>{$i['item_codigo']}</td><td>{$i['descricao']}</td><td>{$i['cor']}</td><td>{$i['quantidade']}</td><td>R$ {$valor_unit}</td><td>R$ {$subtotal}</td></tr>";
}
$html .= "</tbody></table>

    <h3 style='text-align:right;'>VALOR GLOBAL DA PROPOSTA: R$ " . number_format($orc['valor_total'], 2, ',', '.') . "</h3>

    <table class='adicionais-table'>
        <tr><td><strong>Validade:</strong></td><td>{$orc['validade']}</td></tr>
        <tr><td><strong>Prazo de entrega:</strong></td><td>{$orc['prazo_entrega']}</td></tr>
        <tr><td><strong>Condição de pagamento:</strong></td><td>{$orc['condicao_pagamento']}</td></tr>
        <tr><td><strong>Garantia:</strong></td><td>{$orc['garantia']}</td></tr>
    </table>

    <div style='margin-top: 20px; padding: 10px; border: 0px;'>
        <strong>Observações:</strong>
        <p>{$orc['observacoes']}</p>
    </div>

    <div class='footer'>
        <p>NOME DA SUA EMPRESA | Frase de impacto da sua empresa.</p>
        <p>CNPJ: 00.000.000/0000-00 - Inscrição Estadual: 000000000 <br>
        Endereço numero, Bairro, Cidade – Estádo Cep.: 00.000-000</p>
    </div>
</body>
</html>";

// 4. Renderização com limpeza de buffer
if (ob_get_length()) ob_end_clean();

$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("Orcamento_" . $orc['id'] . ".pdf", ["Attachment" => true]);
exit;