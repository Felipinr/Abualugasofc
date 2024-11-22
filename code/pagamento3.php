<?php
require_once 'conexao.php';
require_once 'core.php';


?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lançar Pagamento</title>
    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script src="js/jquery.mask.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="estilos/style.css" />
</head>

<body>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Lançar Pagamento</h2>
        <form id="formPagamento" action="pagamento_terminar.php" method="POST">
            <input type="hidden" name="id_aluguel">

            <div class="mb-3">
                <label for="data_pagamento" class="form-label">Data Atual:</label>
                <input type="date" name="data_pagamento" class="form-control" required>
            </div>

             <option value='$metodo_nome'>$metodo_pagamento</option>

            <div class="mb-3">
                <label for="preco_por_km" class="form-label">Preço por KM:</label>
                <input type="number" name="preco_por_km" step="0.01" min="0" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="kmtotaldoaluguel" class="form-label">Total de KM do Aluguel:</label>
                <span id="kmtotaldoaluguel">0.00</span> KM
            </div>

            <div class="mb-3">
                <label for="valor" class="form-label">Valor Final:</label>
                <input type="number" name="valor" step="0.01" min="0" class="form-control" readonly>
            </div>

            <h4>Veículos</h4>
            <hr>
            <?php

            $carros = listarVeiculosEmprestimo($conexao, $_GET['id_aluguel']);

            foreach ($carros as $carroEmprestimo) {
                $veiculo = listarVeiculoPorId($conexao, $carroEmprestimo[0]);

                if (count($veiculo) === 12) {
                    echo "<input type='hidden' name='id_veiculo[]' value='{$veiculo[0]}'>";
                    echo "<div class='mb-3'>";
                    echo "<p><strong>Veículo:</strong> {$veiculo[1]} - {$veiculo[2]}</p>";
                    echo "<p><strong>Km Atual:</strong> <span class='km-atual'>{$veiculo[11]}</span></p>";
                    echo "<label for='kmpercorrido' class='form-label'>Km Percorrido:</label>";
                    echo "<input type='number' name='kmpercorrido[]' class='form-control kmpercorrido' step='0.01' min='0' required>";
                    echo "<p><strong>Nova Quilometragem:</strong> <span class='nova-km'>0.00</span></p>";
                    echo "</div>";
                    echo "<hr>";
                } else {
                    echo "<p>Erro ao carregar informações do veículo.</p>";
                }
            }
            ?>

            <div class="text-center">
                <input type="submit" value="Lançar Pagamento" class="btn btn-primary">
            </div>
        </form>
         <div class="text-center mt-4">
        <a href="pagamento.php" class="btn btn-secondary">Voltar</a>
        <a href="index.html" class="btn btn-secondary">Voltar ao início</a>
    </div>
    </div>

    <script>
        $(document).ready(function() {
            $(".kmpercorrido").on("input", function() {
                const kmAtual = parseFloat($(this).closest(".mb-3").find(".km-atual").text());
                const kmPercorrido = parseFloat($(this).val()) || 0;
                const novaKm = kmAtual + kmPercorrido;
                $(this).closest(".mb-3").find(".nova-km").text(novaKm.toFixed(2));
            });

            function calcularValor() {
                let totalKmPercorrido = 0;

                $(".kmpercorrido").each(function() {
                    totalKmPercorrido += parseFloat($(this).val()) || 0;
                });

                const precoPorKm = parseFloat($("input[name='preco_por_km']").val()) || 0;
                const valorFinal = totalKmPercorrido * precoPorKm;

                $("input[name='valor']").val(valorFinal.toFixed(2));
                $("#kmtotaldoaluguel").text(totalKmPercorrido.toFixed(2));
            }

            $(".kmpercorrido, input[name='preco_por_km']").on("input", calcularValor);

            calcularValor();

            $("#formPagamento").validate({
                rules: {
                    preco_por_km: {
                        required: true,
                        number: true,
                        min: 0,
                    },
                    data_pagamento: {
                        required: true,
                    },
                },
                messages: {
                    preco_por_km: {
                        required: "O preço do km rodado do é obrigatório.",
                        number: "O preço do km deve ser um número válido.",
                        min: "O preço não pode ser um valor negativo",
                    },
                    data_pagamento: {
                        required: "Informe a data em que o pagamento foi feito.",
                    },
                },
            });
        });
    </script>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>