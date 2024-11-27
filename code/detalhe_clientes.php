<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Cliente</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        select, input[type="submit"] {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h2>Detalhes do Cliente</h2>

    <?php
    require_once 'conexao.php';  

    /**
     * Função para exibir detalhes do cliente e seus aluguéis.
     *
     * Esta função busca as informações de um cliente com base no ID fornecido na URL. Também exibe os aluguéis desse cliente,
     * além de permitir a seleção de um funcionário para registrar um empréstimo.
     *
     * @param mysqli    $conexao    Conexão com o banco de dados.
     * @param int       $id_cliente ID do cliente cujos detalhes serão exibidos.
     * @return void
     */


    function exibirDetalhesCliente($conexao, $id_cliente) {
        if ($id_cliente) {
            $queryCliente = "SELECT nome FROM clientes WHERE id_cliente = '$id_cliente'";
            $resultCliente = mysqli_query($conexao, $queryCliente);

            if ($resultCliente && mysqli_num_rows($resultCliente) > 0) {
                $cliente = mysqli_fetch_assoc($resultCliente);
                echo "<p><strong>Nome do Cliente:</strong> " . $cliente['nome'] . "</p>";

                $queryAlugueis = "SELECT id_aluguel, modelo_carro, km_inicial, km_final, valor_km FROM alugueis WHERE id_cliente = '$id_cliente'";
                $resultAlugueis = mysqli_query($conexao, $queryAlugueis);

                echo "<h3>Aluguéis do Cliente</h3>";

                if ($resultAlugueis && mysqli_num_rows($resultAlugueis) > 0) {
                    $alugueis = mysqli_fetch_all($resultAlugueis, MYSQLI_ASSOC); 

                    echo '<ul>';
                    foreach ($alugueis as $aluguel) {
                        echo '<li>';
                        echo 'ID Aluguel: ' . $aluguel['id_aluguel'] . ' - Carro: ' . $aluguel['modelo_carro'];
                        echo ' - Km Inicial: ' . $aluguel['km_inicial'] . ' - Km Final: ' . $aluguel['km_final'];
                        echo ' - Valor por Km: R$ ' . $aluguel['valor_km'];
                        echo '</li>';
                    }
                    echo '</ul>';
                } else {
                    echo "<p>Não há aluguéis para este cliente.</p>";
                }

                $queryFuncionarios = "SELECT id_funcionario, nome FROM funcionarios";
                $resultFuncionarios = mysqli_query($conexao, $queryFuncionarios);

                if ($resultFuncionarios) {
                    $funcionarios = mysqli_fetch_all($resultFuncionarios, MYSQLI_ASSOC);  
                    
                    echo '<form method="POST" action="processar_emprestimo.php">';
                    echo '<input type="hidden" name="id_cliente" value="' . $id_cliente . '">';
                    echo '<label for="id_funcionario">Escolha o Funcionário:</label>';
                    echo '<select id="id_funcionario" name="id_funcionario" required>';
                    foreach ($funcionarios as $funcionario) {
                        echo '<option value="' . $funcionario['id_funcionario'] . '">' . $funcionario['nome'] . '</option>';
                    }
                    echo '</select>';
                    echo '<input type="submit" value="Registrar Empréstimo">';
                    echo '</form>';
                } else {
                    echo "<p>Erro ao buscar funcionários: " . mysqli_error($conexao) . "</p>";
                }
            } else {
                echo "<p>Cliente não encontrado.</p>";
            }

            mysqli_close($conexao);
        } else {
            echo "<p>ID do cliente não fornecido.</p>";
        }
    }

    $id_cliente = isset($_GET['id_cliente']) ? $_GET['id_cliente'] : '';

    exibirDetalhesCliente($conexao, $id_cliente);
    ?>
</body>
</html>
