<?php 
require 'auth.php'; 
require 'db.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM orcamentos WHERE id = ?");
$stmt->execute([$id]);
$orc = $stmt->fetch();

$stmt_itens = $pdo->prepare("SELECT * FROM orcamento_itens WHERE orcamento_id = ?");
$stmt_itens->execute([$id]);
$itens = $stmt_itens->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card p-4 shadow">
            <h2>Editar Orçamento #<?php echo $id; ?></h2>
            <form action="atualizar_orcamento" method="POST">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>Tipo:</label>
                        <select name="tipo_documento" class="form-select">
                            <option value="ORC" <?php echo ($orc['tipo_documento'] == 'ORC') ? 'selected' : ''; ?>>Orçamento</option>
                            <option value="PRO" <?php echo ($orc['tipo_documento'] == 'PRO') ? 'selected' : ''; ?>>Proposta</option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <label>Cliente:</label>
                        <input type="text" name="cliente_nome" class="form-control" value="<?php echo htmlspecialchars($orc['cliente_nome']); ?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Documento (CPF/CNPJ):</label>
                        <input type="text" name="cliente_documento" class="form-control" value="<?php echo htmlspecialchars($orc['cliente_documento']); ?>">
                    </div>
                    <div class="col-md-6">
                        <label>Data:</label>
                        <input type="date" name="data_emissao" class="form-control" value="<?php echo $orc['data_emissao']; ?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label>Objeto da Proposta:</label>
                        <textarea name="objeto_proposta" class="form-control" rows="2"><?php echo htmlspecialchars($orc['objeto_proposta']); ?></textarea>
                    </div>
                </div>

                <table class="table table-bordered" id="tabela-itens">
                    <thead class="table-dark">
                        <tr>
                            <th>Código</th><th>Descrição</th><th>Cor</th><th>Qtd.</th><th>Vl. Unit.</th><th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($itens as $i) { ?>
                        <tr>
                            <td><input type="text" name="codigo[]" value="<?php echo $i['item_codigo']; ?>" class="form-control form-control-sm"></td>
                            <td><input type="text" name="descricao[]" value="<?php echo $i['descricao']; ?>" class="form-control form-control-sm"></td>
                            <td><input type="text" name="cor[]" value="<?php echo $i['cor']; ?>" class="form-control form-control-sm"></td>
                            <td><input type="number" name="qtd[]" value="<?php echo $i['quantidade']; ?>" class="form-control form-control-sm qtd" oninput="calcularTotal()"></td>
                            <td><input type="number" name="valor_unit[]" value="<?php echo $i['valor_unitario']; ?>" class="form-control form-control-sm valor_unit" oninput="calcularTotal()"></td>
                            <td class="subtotal"><?php echo number_format($i['subtotal'], 2, '.', ''); ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>

                <button type="button" class="btn btn-secondary" onclick="adicionarLinha()">+ Adicionar Item</button>
                <hr>
                <h4>Total Geral: R$ <span id="total-geral"><?php echo number_format($orc['valor_total'], 2, '.', ''); ?></span></h4>
                <input type="hidden" name="valor_total" id="valor_total_input" value="<?php echo $orc['valor_total']; ?>">

                <div class="row mb-3">
                    <div class="col-md-6"><label>Validade:</label><input type="text" name="validade" class="form-control" value="<?php echo $orc['validade']; ?>"></div>
                    <div class="col-md-6"><label>Prazo de entrega:</label><input type="text" name="prazo_entrega" class="form-control" value="<?php echo $orc['prazo_entrega']; ?>"></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6"><label>Condição de pagamento:</label><input type="text" name="condicao_pagamento" class="form-control" value="<?php echo $orc['condicao_pagamento']; ?>"></div>
                    <div class="col-md-6"><label>Garantia:</label><input type="text" name="garantia" class="form-control" value="<?php echo $orc['garantia']; ?>"></div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg">Atualizar Orçamento</button>
            </form>
        </div>
    </div>

    <script>
        function adicionarLinha() {
            let tabela = document.getElementById("tabela-itens").getElementsByTagName('tbody')[0];
            let novaLinha = tabela.insertRow();
            novaLinha.innerHTML = `
                <td><input type="text" name="codigo[]" class="form-control form-control-sm"></td>
                <td><input type="text" name="descricao[]" class="form-control form-control-sm"></td>
                <td><input type="text" name="cor[]" class="form-control form-control-sm"></td>
                <td><input type="number" name="qtd[]" class="form-control form-control-sm qtd" oninput="calcularTotal()"></td>
                <td><input type="number" name="valor_unit[]" class="form-control form-control-sm valor_unit" oninput="calcularTotal()"></td>
                <td class="subtotal">0.00</td>
            `;
        }

        function calcularTotal() {
            let linhas = document.querySelectorAll("#tabela-itens tbody tr");
            let totalGeral = 0;
            linhas.forEach(linha => {
                let qtd = linha.querySelector(".qtd").value || 0;
                let valor = linha.querySelector(".valor_unit").value || 0;
                let sub = qtd * valor;
                linha.querySelector(".subtotal").innerText = sub.toFixed(2);
                totalGeral += sub;
            });
            document.getElementById("total-geral").innerText = totalGeral.toFixed(2);
            document.getElementById("valor_total_input").value = totalGeral.toFixed(2);
        }
    </script>
</body>
</html>