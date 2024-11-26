<?php
require_once 'conexao.php';
require_once 'core.php';

// Verifica se o id_cliente foi enviado
if (!isset($_GET['id_cliente']) || empty($_GET['id_cliente'])) {
    echo "<div class='alert alert-danger' role='alert'>Cliente não selecionado. Por favor, volte e selecione um cliente.</div>";
    exit;
}

$id_cliente = $_GET['id_cliente'];

// Chama a função para listar os empréstimos
$emprestimos = listarEmprestimoCliente($conexao, $id_cliente);

// Função para tratar valores nulos
function tratarValor($valor, $default = 0) {
    return isset($valor) && !is_null($valor) ? $valor : $default;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/style.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
<div class="container mt-5">
    <h3 class="text-center mb-4">Registrar Pagamento</h3>

    <?php if (count($emprestimos) > 0): ?>
        <form id="pagamentoForm" action="salvar_pagamento.php" method="POST">
            <div class="mb-3">
                <label for="metodo_pagamento" class="form-label">Método de Pagamento:</label>
                <select class="form-select" id="metodo_pagamento" name="metodo_pagamento" required>
                    <option value="Dinheiro">Dinheiro</option>
                    <option value="Cartao">Cartão</option>
                    <option value="Pix">Pix</option>
                    <option value="Outro">Outro</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="data_pagamento" class="form-label">Data de Pagamento:</label>
                <input type="date" class="form-control" id="data_pagamento" name="data_pagamento" required>
            </div>

            <div class="mb-3">
                <h5>Veículos:</h5>
                <?php foreach ($emprestimos as $emprestimo): ?>
                    <div class="mb-3 border p-3 rounded">
                        <strong>Modelo:</strong> <?= $emprestimo['modelo']; ?> <br>
                        <strong>Placa:</strong> <?= $emprestimo['placa']; ?> <br>
                        <strong>Km Inicial:</strong> <?= tratarValor($emprestimo['km_atual'], 0); ?> <br>
                        <strong>Valor por Km:</strong> R$ <?= tratarValor($emprestimo['valor_km'], 0); ?> <br>

                        <label for="km_final_<?= $emprestimo['id_aluguel']; ?>" class="form-label mt-2">Km Final:</label>
                        <input type="number" class="form-control km_final" id="km_final_<?= $emprestimo['id_aluguel']; ?>"
                               name="km_final[<?= $emprestimo['id_aluguel']; ?>]" 
                               min="<?= tratarValor($emprestimo['km_atual'], 0); ?>"
                               data-km-inicial="<?= tratarValor($emprestimo['km_atual'], 0); ?>"
                               data-valor-km="<?= tratarValor($emprestimo['valor_km'], 0); ?>" required>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="mb-3">
                <button type="button" id="calcularTotal" class="btn btn-primary">Calcular Total</button>
                <h5 class="mt-3">Total a Pagar: R$ <span id="totalValor">0.00</span></h5>
            </div>

            <button type="submit" class="btn btn-success">Registrar Pagamento</button>
        </form>
    <?php else: ?>
        <div class="alert alert-warning" role="alert">Não há empréstimos para este cliente.</div>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="index.html" class="btn btn-secondary">Início</a>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#calcularTotal').on('click', function () {
            let total = 0;

            $('.km_final').each(function () {
                const kmInicial = parseFloat($(this).data('km-inicial'));
                const valorKm = parseFloat($(this).data('valor-km'));
                const kmFinal = parseFloat($(this).val());

                if (!isNaN(kmFinal) && kmFinal > kmInicial) {
                    const kmRodados = kmFinal - kmInicial;
                    total += kmRodados * valorKm;
                }
            });

            $('#totalValor').text(total.toFixed(2).replace('.', ','));
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
