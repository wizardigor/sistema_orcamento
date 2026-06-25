<?php require 'auth.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <span class="navbar-brand">Sistema ICM</span>
            <a href="logout" class="btn btn-outline-danger btn-sm">Sair</a>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="card p-4 shadow">
            <h2>Novo Orçamento</h2>
            <form action="salvar_orcamento" method="POST">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>Tipo:</label>
                        <select name="tipo_documento" class="form-select">
                            <option value="ORC">Orçamento</option>
                            <option value="PRO">Proposta</option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <label>Cliente:</label>
                        <input type="text" name="cliente_nome" class="form-control" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="col-md-3">
                            <label>Tipo de Documento:</label>
                            <select name="tipo_doc" class="form-control">
                                <option value="CPF">CPF</option>
                                <option value="CNPJ">CNPJ</option>
                            </select>
                        </div>
                        <div class="col">
                            <label>Número do Documento:</label>
                            <input type="text" name="cliente_documento" class="form-control" placeholder="Apenas números">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>Data:</label>
                        <input type="date" name="data_emissao" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label>Objeto da Proposta:</label>
                        <textarea name="objeto_proposta" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label>Observações:</label>
                        <textarea name="observacoes" class="form-control" rows="2">Os valores apresentados referem-se exclusivamente aos itens descritos nesta proposta. Qualquer alteração de quantidade, especificação ou condição comercial poderá gerar revisão do orçamento.</textarea>
                    </div>
                </div>

                <table class="table table-bordered" id="tabela-itens">
                    <thead class="table-dark">
                        <tr>
                            <th>Código</th>
                            <th>Descrição</th>
                            <th>Cor</th>
                            <th>Qtd.</th>
                            <th>Vl. Unit.</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

                <button type="button" class="btn btn-secondary" onclick="adicionarLinha()">+ Adicionar Item</button>

                <hr>
                <h4>Total Geral: R$ <span id="total-geral">0.00</span></h4>
                <input type="hidden" name="valor_total" id="valor_total_input">

                <hr>
                <h5>Informações Adicionais</h5>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Validade da proposta:</label>
                        <input type="text" name="validade" class="form-control" value="15 dias">
                    </div>
                    <div class="col-md-6">
                        <label>Prazo de entrega:</label>
                        <input type="text" name="prazo_entrega" class="form-control" value="Após confirmação do pagamento">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Condição de pagamento:</label>
                        <input type="text" name="condicao_pagamento" class="form-control" value="A vista (TED ou PIX)">
                    </div>
                    <div class="col-md-6">
                        <label>Garantia:</label>
                        <input type="text" name="garantia" class="form-control" value="Contra defeitos de fabricação">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg">Salvar Orçamento</button>
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