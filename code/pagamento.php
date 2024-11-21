<?php
require_once 'conexao.php';

/**
 * Exibe um formulário para selecionar um cliente e listar os veículos alugados por ele.
 * 
 * @param mysqli        $conexao Conexão ativa com o banco de dados.
 * @param int           $id_cliente ID do cliente selecionado.
 * @return void
 */

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selecionar Cliente e Veículos Alugados</title>
    <script src="jquery-3.7.1.min.js"></script> <!-- Mantém a versão completa do jQuery -->
    <script src="jquery.validate.min.js"></script> <!-- jQuery Validation -->
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

        th,
        td {
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
        #cliente-error{
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: rgba(255, 0, 0, 0.8); /* Cor de fundo vermelha com opacidade */
            color: white;
            font-size: 16px;
            padding: 10px;
            text-align: center;
            font-weight: bold;
            z-index: 1000; /* Garante que a mensagem fique acima dos outros elementos */
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Selecione um Cliente e Veículos Alugados</h1>

        <form id="form" method="POST" action="">
            <label for="cliente">Escolha um Cliente:</label>
            <select name="cliente" id="cliente" required>
                <option value="">Selecione um cliente</option>
                <?php
                $query_clientes = "SELECT id_cliente, nome FROM clientes";
                $result_clientes = mysqli_query($conexao, $query_clientes);

                while ($row = mysqli_fetch_assoc($result_clientes)) {
                    echo "<option value='{$row['id_cliente']}'>{$row['nome']}</option>";
                }
                ?>
            </select>
            <button type="submit" class="btn">Mostrar Veículos Alugados</button>
        </form>

        <?php
        if (isset($_POST['cliente']) && !empty($_POST['cliente'])) {
            $id_cliente = intval($_POST['cliente']);

            $sql_veiculos = "
                SELECT v.id_veiculo, v.modelo, v.km_atual
                FROM veiculos v
                JOIN alugueis_veiculos av ON v.id_veiculo = av.veiculos_id_veiculo
                JOIN alugueis a ON av.alugueis_id_aluguel = a.id_aluguel
                WHERE a.id_cliente = $id_cliente";

            $result_veiculos = mysqli_query($conexao, $sql_veiculos);

            if (mysqli_num_rows($result_veiculos) > 0) {
                echo "<table>
                        <tr>
                            <th>Modelo</th>
                            <th>KM Inicial</th>
                        </tr>";

                while ($veiculo = mysqli_fetch_assoc($result_veiculos)) {
                    echo "<tr>
                            <td>" . htmlspecialchars($veiculo['modelo']) . "</td>
                            <td>" . htmlspecialchars($veiculo['km_atual']) . "</td>
                          </tr>";
                }
                echo "</table>";
            } else {
                echo "<p>Nenhum veículo alugado encontrado para esse cliente.</p>";
            }
        }
        echo "<a href='pagamento2.php?id_cliente='class='btn'>Pagamento</a>";

        ?>
<script>
    $(document).ready(function () {
        $("#form").validate({
            rules: {
                cliente: {
                    required: true
                }
            },
            messages: {
                cliente: {
                    required: "Selecione um cliente para realizar o pagamento do aluguel"
                }
            }
        });
    });
</script>
    </div>

</body>

</html>