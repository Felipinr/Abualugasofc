<?php
// Incluir o arquivo de conexão com o banco de dados
require_once('conexao.php');

// 1. Selecionar todos os clientes em ordem alfabética
$sql_clientes = "SELECT id_cliente, nome FROM clientes ORDER BY nome ASC";


?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selecionar Cliente e Veículos Alugados</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        
        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        
        h1 {
            text-align: center;
            color: #333;
        }
        
        select {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 20px;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Selecione um Cliente e Veículos Alugados</h1>

        <form method="POST" action="pagamento.php">
            <label for="cliente">Escolha um Cliente:</label>
            <select name="cliente" id="cliente">
                <option value="">Selecione...</option>
                <?php
                // Exibir os clientes em ordem alfabética
                while ($cliente = $result_clientes->fetch_assoc()) {
                    echo "<option value='" . $cliente['id_cliente'] . "'>" . htmlspecialchars($cliente['nome']) . "</option>";
                }
                ?>
            </select>
            <button type="submit" class="btn">Mostrar Veículos Alugados</button>
        </form>

        <?php
        // Verificar se um cliente foi selecionado e mostrar os veículos alugados
        if (isset($_POST['cliente']) && !empty($_POST['cliente'])) {
            $id_cliente = $_POST['cliente'];

            // 2. Consultar os veículos alugados pelo cliente selecionado
            $sql_veiculos = "SELECT v.id_veiculo, v.modelo, av.km_inicial
                             FROM veiculos v
                             JOIN alugueis_veiculos av ON v.id_veiculo = av.veiculos_id_veiculo
                             WHERE av.cliente_id = $id_cliente"; // Substitua 'cliente_id' pelo nome correto da coluna, se necessário.

            $result_veiculos = $mysqli->query($sql_veiculos);

            // Verificar se a consulta foi bem-sucedida
            if ($result_veiculos === false) {
                echo "Erro ao executar a consulta de veículos: " . $mysqli->error;
                exit;
            }

            // Exibir os veículos alugados
            if ($result_veiculos->num_rows > 0) {
                echo "<table>
                        <tr>
                            <th>Modelo</th>
                            <th>KM Inicial</th>
                        </tr>";

                while ($veiculo = $result_veiculos->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($veiculo['modelo']) . "</td>
                            <td>" . htmlspecialchars($veiculo['km_inicial']) . "</td>
                          </tr>";
                }
                echo "</table>";
            } else {
                echo "<p>Nenhum veículo alugado encontrado para esse cliente.</p>";
            }
        }
        ?>
    </div>
</body>
</html>

<?php
// Fechar a conexão com o banco de dados
if (isset($mysqli)) {
    $mysqli->close();
}
?>
