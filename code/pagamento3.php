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
function tratarValor($valor, $default = 0)
{
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .navbar {
            background-color: #343a40;
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }

        .navbar-nav .nav-link:hover {
            background-color: #495057;
            border-radius: 5px;
        }

        .footer {
            margin-top: auto;
            padding: 20px 0;
            background-color: #343a40;
            color: white;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.html">Carromeu e Julieta</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <div class="container mt-5">
        <h3 class="text-center mb-4">Registrar Pagamento</h3>

        <?php if (count($emprestimos) > 0): ?>
            <form id="pagamentoForm" action="salvar_pagamento.php" method="POST">
                <div class="mb-3">
                    <label for="metodo_pagamento" class="form-label">Método de Pagamento:</label>
                    <select class="form-select" id="metodo_pagamento" name="metodo_pagamento" required>
                        <option value="">Selecione...</option>
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

                            <strong>Valor por Km:</strong> R$
                            <?= isset($emprestimo['valor_km']) ? tratarValor($emprestimo['valor_km'], 0) : 'Valor não definido'; ?> <br>

                            <label for="km_final_<?= $emprestimo['id_aluguel']; ?>" class="form-label mt-2">Km Final:</label>
                            <input type="number" class="form-control km_final" id="km_final_<?= $emprestimo['id_aluguel']; ?>"
                                name="km_final[<?= $emprestimo['id_aluguel']; ?>]"
                                min="<?= tratarValor($emprestimo['km_atual'], 0); ?>"
                                data-km-inicial="<?= tratarValor($emprestimo['km_atual'], 0); ?>"
                                data-valor-km="<?= isset($emprestimo['valor_km']) ? tratarValor($emprestimo['valor_km'], 0) : 0; ?>"
                                required>
                        </div>
                    <?php endforeach; ?>
                </div>

                <input type="hidden" id="valor_pagamento" name="valor_pagamento">
                <input type="hidden" id="id_aluguel" name="id_aluguel">

                <div class="mb-3">
                    <button type="button" id="calcularTotal" class="btn btn-primary">Calcular Total</button>
                    <h5 class="mt-3">Total a Pagar: R$ <span id="totalValor">0.00</span></h5>
                </div>

                <button type="submit" class="btn btn-success" id="registrarPagamento">Registrar Pagamento</button>
            </form><br>
        <?php else: ?>
            <div class="alert alert-warning" role="alert">Não há empréstimos para este cliente.</div>
        <?php endif; ?>

    </div>

    <script>
        $(document).ready(function() {
            $('#calcularTotal').on('click', function() {
                let total = 0;
                let idAluguel = null;

                $('.km_final').each(function() {
                    const kmInicial = parseFloat($(this).data('km-inicial'));
                    const valorKm = parseFloat($(this).data('valor-km'));
                    const kmFinal = parseFloat($(this).val());
                    idAluguel = $(this).attr('id').split('_')[2];

                    if (!isNaN(kmFinal) && kmFinal > kmInicial) {
                        const kmRodados = kmFinal - kmInicial;
                        total += kmRodados * valorKm;
                    }
                });

                $('#totalValor').text(total.toFixed(2).replace('.', ','));
                $('#valor_pagamento').val(total.toFixed(2));
                $('#id_aluguel').val(idAluguel);
            });
        });
    </script>

    <!-- Footer -->
    <footer class="footer text-center">
        <p>© 2024 Carromeu e Julieta - Todos os direitos reservados</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
