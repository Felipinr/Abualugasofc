<?php
require_once 'conexao.php';

/**
 * Obtém a lista de clientes ordenados por nome.
 */
$sql_clientes = "SELECT id_cliente, nome FROM clientes ORDER BY nome";
$stmt_clientes = $conexao->prepare($sql_clientes);
$stmt_clientes->execute();
$result_clientes = $stmt_clientes->get_result();

$id_cliente = null;
$nome_cliente = "";

if (isset($_POST['id_cliente'])) {
    $id_cliente = $_POST['id_cliente'];

    /**
     * Obtém o nome do cliente selecionado.
     * 
     * @param int $id_cliente ID do cliente selecionado.
     */

    $sql_nome_cliente = "SELECT nome FROM clientes WHERE id_cliente = ?";
    $stmt_nome_cliente = $conexao->prepare($sql_nome_cliente);
    $stmt_nome_cliente->bind_param("i", $id_cliente);
    $stmt_nome_cliente->execute();
    $result_nome_cliente = $stmt_nome_cliente->get_result();

    if ($row_nome_cliente = $result_nome_cliente->fetch_assoc()) {
        $nome_cliente = $row_nome_cliente['nome'];
    }

    /**
     * Obtém os aluguéis do cliente selecionado.
     * 
     * @param int $id_cliente ID do cliente selecionado.
     */

    $sql = "SELECT 
                 av.veiculos_id_veiculo, 
                 v.modelo, 
                 av.km_inicial
             FROM alugueis_veiculos av
             JOIN veiculos v ON av.veiculos_id_veiculo = v.id_veiculo
             JOIN alugueis a ON a.id_aluguel = av.id_aluguel
             WHERE a.id_cliente = ?";


    $sql = "SELECT modelo, km_atual FROM veiculos WHERE id_veiculo = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id_cliente);
    $stmt->execute();
    $result = $stmt->get_result();


}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento de Empréstimo</title>
    <style>
        /* Estilos básicos para a página */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        .container {
            width: 60%;
            background-color: #fff;
            margin: 0 auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1,
        h2 {
            text-align: center;
            color: #333;
        }

        form {
            margin-bottom: 20px;
        }

        select,
        input[type="radio"],
        button {
            display: block;
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        select:focus,
        input[type="radio"]:focus,
        button:focus {
            border-color: #28a745;
            outline: none;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f8f8f8;
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
        <h1>Selecionar Cliente para Pagamento de Empréstimo</h1>
        <form method="POST" action="">
            <select name="id_cliente" required>
                <option value="">Selecione um cliente</option>
                <?php while ($cliente = $result_clientes->fetch_assoc()): ?>
                    <option value="<?php echo $cliente['id_cliente']; ?>" <?php echo ($cliente['id_cliente'] == $id_cliente) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cliente['nome']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <button type="submit">Buscar</button>
        </form>

        <?php if ($id_cliente): ?>
            <h2>Cliente Consultado: <?php echo htmlspecialchars($nome_cliente); ?></h2>
        <?php endif; ?>

        <?php if (isset($result) && $result->num_rows > 0): ?>
            <h2>Veículos Alugados</h2>
            <form method="POST" action="informar_km_final.php">
                <table>
                    <tr>
                        <th>Selecionar</th>
                        <th>Modelo do Veículo</th>
                        <th>Km Inicial</th>
                    </tr>
                    <?php while ($row = $result->fetch_assoc()):
                        var_dump($row); ?>
                       <tr>
    <td>
        <input type="checkbox" name="aluguel_selecionado[]" value="<?php echo $row['veiculos_id_veiculo']; ?>">
    </td>
    <td><?php echo htmlspecialchars($row['modelo']); ?></td>
    <td><?php echo htmlspecialchars($row['km_atual']); ?></td>
</tr>


                    <?php endwhile; ?>
                </table>
                <button type="submit">Informar Km Final</button>
            </form>
        <?php elseif (isset($result)): ?>
            <p>Nenhum aluguel encontrado para o cliente selecionado.</p>
        <?php endif; ?>
    </div>
</body>

</html>

<?php
$stmt_clientes->close();
if (isset($stmt)) {
    $stmt->close();
}
if (isset($stmt_nome_cliente)) {
    $stmt_nome_cliente->close();
}
$conexao->close();
?>