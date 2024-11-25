<?php
require_once 'conexao.php';
require_once 'core.php';
require_once 'login2.php';

$id_aluguel = $_GET['alugueis_id_aluguel'];
$veiculos = listarVeiculosEmprestimos($conexao, $id_aluguel);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calcular Pagamento</title>
    <script src="js/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="estilos/style.css">
</head>

<body>
    <div class="container">
        <h2 class="text-center">Calcular Pagamento</h2>
        <form id="formPagamento" action="salvar_pagamento.php" method="POST">
            <input type="hidden" name="id_aluguel" value="<?= $id_aluguel ?>">

            <?php foreach ($veiculos as $veiculo): ?>
                <div class="veiculo-item">
                    <h4>Veículo: <?= $veiculo['modelo'] ?> (<?= $veiculo['placa'] ?>)</h4>
                    <p><strong>Km Inicial:</strong> <?= $veiculo['km_inicial'] ?> KM</p>
                    <p><strong>Preço por KM:</strong> R$ <?= number_format($veiculo['preco_por_km'], 2, ',', '.') ?></p>

                    <div class="mb-3">
                        <label for="km_final_<?= $veiculo['id_veiculo'] ?>">Km Final:</label>
                        <input type="number" name="km_final[<?= $veiculo['id_veiculo'] ?>]" class="form-control km-final" min="<?= $veiculo['km_inicial'] ?>" required>
                    </div>

                    <p><strong>Km Percorrido:</strong> <span class="km-percorrido">0.00</span> KM</p>
                    <p><strong>Valor do Veículo:</strong> R$ <span class="valor-veiculo">0.00</span></p>
                    <hr>
                </div>
            <?php endforeach; ?>

            <h4>Total: R$ <span id="valor-total">0.00</span></h4>
            <button type="submit" class="btn btn-primary">Salvar Pagamento</button>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const veiculoItems = document.querySelectorAll(".veiculo-item");

            veiculoItems.forEach(item => {
                const kmFinalInput = item.querySelector(".km-final");
                const kmPercorridoSpan = item.querySelector(".km-percorrido");
                const valorVeiculoSpan = item.querySelector(".valor-veiculo");

                kmFinalInput.addEventListener("input", () => {
                    const kmInicial = parseFloat(item.querySelector("p").textContent.match(/\d+/)[0]);
                    const precoPorKm = parseFloat(item.querySelector("strong + p").textContent.replace("R$", "").replace(",", "."));

                    const kmFinal = parseFloat(kmFinalInput.value) || kmInicial;
                    const kmPercorrido = kmFinal - kmInicial;
                    const valor = kmPercorrido * precoPorKm;

                    kmPercorridoSpan.textContent = kmPercorrido.toFixed(2);
                    valorVeiculoSpan.textContent = valor.toFixed(2);

                    calcularTotal();
                });
            });

            function calcularTotal() {
                let total = 0;

                document.querySelectorAll(".valor-veiculo").forEach(span => {
                    total += parseFloat(span.textContent) || 0;
                });

                document.getElementById("valor-total").textContent = total.toFixed(2);
            }
        });
    </script>
</body>

</html>
