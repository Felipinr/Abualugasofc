<?php
require_once 'conexao.php';

if (isset($_POST['id_aluguel'], $_POST['km_inicial'], $_POST['km_final'], $_POST['valor_km'])) {
    $id_aluguel = $_POST['id_aluguel'];
    $km_inicial = $_POST['km_inicial'];
    $km_final = $_POST['km_final'];
    $valor_km = $_POST['valor_km'];

    /**
     * Função para calcular o valor total do aluguel baseado no quilômetro rodado.
     *
     * @param int     $km_inicial   Quilometragem inicial do veículo no momento do aluguel.
     * @param int     $km_final     Quilometragem final do veículo no término do aluguel.
     * @param float   $valor_km     Valor cobrado por quilômetro rodado.
     * @return float                Valor total a ser pago, baseado no valor por quilômetro.
     */

     
    // Verifica se a quilometragem final não é menor que a inicial
    if ($km_final < $km_inicial) {
        echo "Km final não pode ser menor que o Km inicial.";
        exit;
    }

    // Calcula os quilômetros rodados e o valor total
    $km_rodados = $km_final - $km_inicial;
    $valor_total = $km_rodados * $valor_km;

    echo "<h1>Resumo do Pagamento</h1>";
    echo "<p>Km Inicial: $km_inicial</p>";
    echo "<p>Km Final: $km_final</p>";
    echo "<p>Km Rodados: $km_rodados</p>";
    echo "<p>Valor por Km: R$ " . number_format($valor_km, 2, ',', '.') . "</p>";
    echo "<p><strong>Valor Total: R$ " . number_format($valor_total, 2, ',', '.') . "</strong></p>";
} else {
    echo "Dados insuficientes para calcular o valor.";
}
?>
