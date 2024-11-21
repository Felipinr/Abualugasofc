<?php
require_once 'conexao.php';

/**
 * Processa o pagamento dos alugueis selecionados.
 * 
 * @param array $alugueis_selecionados IDs dos alugueis selecionados.
 * @param array $km_final              Quilometragem final dos veículos.
 * @param string $metodo_pagamento     Método de pagamento escolhido.
 */
if (isset($_POST['alugueis_selecionados']) && !empty($_POST['alugueis_selecionados']) && isset($_POST['km_final']) && isset($_POST['metodo_pagamento'])) {
    $alugueis_selecionados = $_POST['alugueis_selecionados'];
    $km_final = $_POST['km_final'];
    $metodo_pagamento = $_POST['metodo_pagamento'];

    $total_geral = 0;

    echo "
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #007BFF;
            color: white;
        }
        td {
            color: #333;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .total {
            background-color: #007BFF;
            color: white;
            font-weight: bold;
        }
    </style>
    ";

    echo "<h1>Resumo do Pagamento</h1>";
    echo "<table border='1'>
            <tr>
                <th>ID Aluguel</th>
                <th>Modelo do Veículo</th>
                <th>Km Rodados</th>
                <th>Método de Pagamento</th>
                <th>Preço Total</th>
                <th>Ação</th>
            </tr>";
    echo "<button onclick=\"window.location.href='index.html'\" style='display: block; margin: 20px auto; padding: 10px 20px; background-color: #007BFF; color: white; border: none; border-radius: 5px; cursor: pointer;'>
        Voltar ao Início
      </button>";

    foreach ($alugueis_selecionados as $id_aluguel) {
        $sql = "SELECT v.id_veiculo, v.modelo, av.km_inicial, a.valor_km 
                FROM alugueis_veiculos av
                JOIN veiculos v ON av.veiculos_id_veiculo = v.id_veiculo
                JOIN alugueis a ON av.alugueis_id_aluguel = a.id_aluguel
                WHERE av.alugueis_id_aluguel = ?";

        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("i", $id_aluguel);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $modelo_veiculo = $row['modelo'];
            $km_inicial = $row['km_inicial'];
            $valor_km = $row['valor_km'];
            $km_final_value = isset($km_final[$id_aluguel]) ? (int)$km_final[$id_aluguel] : 0;

            $km_rodado = $km_final_value - $km_inicial;

            $preco_total = $km_rodado * $valor_km;
            $total_geral += $preco_total;

            echo "<tr>
                    <td>{$id_aluguel}</td>
                    <td>{$modelo_veiculo}</td>
                    <td>{$km_rodado}</td>
                    <td>{$metodo_pagamento}</td>
                    <td>R$ " . number_format($preco_total, 2, ',', '.') . "</td>
                    <td>
                      <form action='deletar_aluguel.php' method='POST' style='display:inline;'>
                        <input type='hidden' name='id_aluguel' value='{$id_aluguel}'>
                        <button type='submit' style='background-color: #dc3545; color: white; border: none; border-radius: 5px; padding: 5px 10px; cursor: pointer;'>Alterar disponiblilidade</button>
                      </form>
                    </td>
                  </tr>";

            $id_veiculo = $row['id_veiculo'];
            $sql_atualizar_disponibilidade = "UPDATE veiculos SET disponivel = 1 WHERE id_veiculo = ?"; // Definido como 1 para indisponível
            $stmt_atualizar = $conexao->prepare($sql_atualizar_disponibilidade);
            $stmt_atualizar->bind_param("i", $id_veiculo);
            $stmt_atualizar->execute();
            $stmt_atualizar->close();
        }

        $stmt->close();
    }

    echo "<tr>
            <td colspan='3'><strong>Total Geral</strong></td>
            <td><strong>R$ " . number_format($total_geral, 2, ',', '.') . "</strong></td>
          </tr>";
    echo "</table>";

    foreach ($alugueis_selecionados as $id_aluguel) {
        $sql_inserir_pagamento = "INSERT INTO pagamentos (valor_pagamento, metodo_pagamento, id_aluguel) VALUES (?, ?, ?)";
        $stmt_pagamento = $conexao->prepare($sql_inserir_pagamento);
        $stmt_pagamento->bind_param("ssi", $preco_total, $metodo_pagamento, $id_aluguel);
        $stmt_pagamento->execute();
        $stmt_pagamento->close();
    }

    $conexao->close();
} else {
    echo "Dados não foram fornecidos corretamente.";
}
