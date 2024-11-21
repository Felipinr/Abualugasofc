<?php
require_once 'conexao.php';
/**
 * Obtém as informações de um aluguel selecionado e exibe um formulário para informar o quilômetro final do veículo.
 *
 * @param mysqli    $conexao                Conexão ativa com o banco de dados.
 * @param int       $id_aluguel             ID do aluguel selecionado.
 * @param int       $veiculos_id_veiculo    ID do veículo alugado.
 * @return void
 */
     
if (isset($_POST['aluguel_selecionado'])) {
    $id_aluguel = $_POST['aluguel_selecionado'];

    // Consulta para obter as informações do aluguel selecionado
    $sql_aluguel = "
        SELECT av.km_inicial, a.valor_km, v.modelo AS modelo_veiculo
        FROM alugueis_veiculos av
        JOIN alugueis a ON av.alugueis_id_aluguel = a.id_aluguel
        JOIN veiculos v ON av.veiculos_id_veiculo = v.id_veiculo
        WHERE a.id_aluguel = ?";


        if (isset($_POST['aluguel_selecionado'])) {
            $veiculos_selecionados = $_POST['aluguel_selecionado'];
            foreach ($veiculos_selecionados as $veiculos_id_veiculo) {
            }
        }
    }
if (isset($_POST['aluguel_selecionado'])) {
    $id_aluguel = $_POST['aluguel_selecionado'];


    $stmt_aluguel = $conexao->prepare($sql_aluguel);
    $stmt_aluguel->bind_param("i", $veiculos_id_veiculo);
    $stmt_aluguel->execute();
    $result_aluguel = $stmt_aluguel->get_result();

    if ($row_aluguel = $result_aluguel->fetch_assoc()) {
        $km_inicial = $row_aluguel['km_inicial'];
        $valor_km = $row_aluguel['valor_km'];
        $modelo_veiculo = $row_aluguel['modelo_veiculo'];
    } else {
        echo "Aluguel não encontrado.";
        exit;
    }
} else {
    echo "Nenhum aluguel selecionado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informar Km Final</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        .container {
            width: 50%;
            background-color: #fff;
            margin: 0 auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            margin-bottom: 20px;
        }

        input[type="number"], button {
            display: block;
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="number"]:focus, button:focus {
            border-color: #28a745;
            outline: none;
        }

        button {
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            padding: 12px;
        }

        button:hover {
            background-color: #218838;
        }

        p {
            text-align: center;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Informar Km Final</h1>
        <p>Veículo: <?php echo ($modelo_veiculo); ?></p>
        <p>Km Inicial: <?php echo ($km_inicial); ?></p>

        <form method="POST" action="calcular_valor.php">
    <input type="hidden" name="id_aluguel" value="<?php echo $id_aluguel; ?>">
    <input type="hidden" name="km_inicial" value="<?php echo $km_inicial; ?>">
    <input type="hidden" name="valor_km" value="<?php echo $valor_km; ?>">
    <label for="km_final">Km Final:</label>
    <input type="number" name="km_final" id="km_final" required>
    <button type="submit">Confirmar Pagamento</button>
</form>
    </div>
</body>
</html>

<?php
$stmt_aluguel->close();
$conexao->close();
?>
