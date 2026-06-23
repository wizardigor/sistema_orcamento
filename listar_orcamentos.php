<?php 
require 'auth.php';
require 'db.php';
// Aqui entraria o include do seu cabeçalho, ex: include 'header.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Orçamentos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Consulta de Orçamentos</h2>
            <a href="novo_orcamento" class="btn btn-primary">+ Novo Orçamento</a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle border">
                <thead class="table-dark">
                    <tr>
                        <th>Nº Documento</th>
                        <th>Cliente</th>
                        <th>Data</th>
                        <th>Valor</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $stmt = $pdo->query("SELECT * FROM orcamentos ORDER BY data_criacao DESC");
                    while($row = $stmt->fetch()) { 
                        $badgeClass = ($row['tipo_documento'] == 'ORC') ? 'bg-primary' : 'bg-success';
                    ?>
                    <tr>
                        <td>
                            <span class="badge <?php echo $badgeClass; ?>">
                                <?php echo $row['tipo_documento'] . '-' . date('Y', strtotime($row['data_emissao'])) . '-' . str_pad($row['numero_sequencial'], 3, '0', STR_PAD_LEFT); ?>
                            </span>
                        </td>
                        <td><strong><?php echo htmlspecialchars($row['cliente_nome']); ?></strong></td>
                        <td><?php echo date('d/m/Y', strtotime($row['data_emissao'])); ?></td>
                        <td>R$ <?php echo number_format($row['valor_total'], 2, ',', '.'); ?></td>
                        <td class="text-center">
                            <a href="gerar_pdf?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" target="_blank">
                                PDF
                            </a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>