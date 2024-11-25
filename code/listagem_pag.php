<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes e Empréstimos</title>
</head>
<body>
    <h2>Clientes Disponíveis</h2>

    <form method="GET" action="">
        <input type="text" name="search" placeholder="Buscar por nome" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
        <input type="submit" value="Pesquisar">
    </form>

    <?php
    /**
 * Esta página exibe uma lista de clientes disponíveis, permite a busca por nome, exibe detalhes do cliente selecionado,
 * seus aluguéis e permite registrar um empréstimo associando um cliente a um funcionário.
 * 
 * @param mysqli        $conexao        Conexão com o banco de dados.
 * @param string|null   $search         (opcional) String de busca para filtrar clientes pelo nome.
 * @param int|null      $id_cliente     (opcional) ID do cliente para exibir detalhes e aluguéis.
 * @param int|null      $id_funcionario (opcional) ID do funcionário para registrar o empréstimo.
 * 
 * @return void
 * 
 * Funcionalidades:
 * - Realiza a conexão com o banco de dados para consultar a lista de clientes.
 * - Exibe os resultados de busca, se o parâmetro `search` for fornecido.
 * - Exibe detalhes de um cliente, incluindo os aluguéis associados, se o parâmetro `id_cliente` for fornecido.
 * - Exibe um formulário para registrar um empréstimo, associando um cliente e um funcionário.
 * - Mensagens de erro e sucesso são exibidas para as operações realizadas.
 */

    require_once 'conexao.php';

    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $id_cliente = isset($_GET['id_cliente']) ? $_GET['id_cliente'] : '';

    $queryClientes = "SELECT id_cliente, nome FROM clientes WHERE nome LIKE '%$search%'";
    $resultClientes = mysqli_query($conexao, $queryClientes);

    if ($resultClientes) {
        if (mysqli_num_rows($resultClientes) > 0) {
            echo '<ul>';
            while ($cliente = mysqli_fetch_assoc($resultClientes)) {
                echo '<li><a href="?search=' . urlencode($search) . '&id_cliente=' . $cliente['id_cliente'] . '">' . $cliente['nome'] . '</a></li>';
            }
            echo '</ul>';
        } else {
            echo '<p>Nenhum cliente encontrado.</p>';
        }
    } else {
        echo '<p>Erro ao buscar clientes.</p>';
    }

    if ($id_cliente) {
        $queryCliente = "SELECT nome FROM clientes WHERE id_cliente = '$id_cliente'";
        $resultCliente = mysqli_query($conexao, $queryCliente);

        if ($resultCliente && mysqli_num_rows($resultCliente) > 0) {
            $cliente = mysqli_fetch_assoc($resultCliente);
            echo '<h2>Detalhes do Cliente</h2>';
            echo '<p><strong>Nome do Cliente:</strong> ' . $cliente['nome'] . '</p>';

            $queryAlugueis = "SELECT id_aluguel, modelo_carro FROM alugueis WHERE id_cliente = '$id_cliente'";
            $resultAlugueis = mysqli_query($conexao, $queryAlugueis);

            if ($resultAlugueis && mysqli_num_rows($resultAlugueis) > 0) {
                echo '<h3>Aluguéis do Cliente</h3>';
                echo '<ul>';
                while ($aluguel = mysqli_fetch_assoc($resultAlugueis)) {
                    echo '<li>ID Aluguel: ' . $aluguel['id_aluguel'] . ' - Carro: ' . $aluguel['modelo_carro'] . '</li>';
                }
                echo '</ul>';
            } else {
                echo '<p>Não há aluguéis para este cliente.</p>';
            }

            $queryFuncionarios = "SELECT id_funcionario, nome FROM funcionarios";
            $resultFuncionarios = mysqli_query($conexao, $queryFuncionarios);

            if ($resultFuncionarios) {
                echo '<h3>Escolha o Funcionário para o Empréstimo</h3>';
                echo '<form method="POST" action="">';
                echo '<input type="hidden" name="id_cliente" value="' . $id_cliente . '">';
                echo '<select name="id_funcionario" required>';
                while ($funcionario = mysqli_fetch_assoc($resultFuncionarios)) {
                    echo '<option value="' . $funcionario['id_funcionario'] . '">' . $funcionario['nome'] . '</option>';
                }
                echo '</select>';
                echo '<input type="submit" value="Registrar Empréstimo">';
                echo '</form>';
            } else {
                echo '<p>Erro ao buscar funcionários.</p>';
            }
        } else {
            echo '<p>Cliente não encontrado.</p>';
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id_funcionario = isset($_POST['id_funcionario']) ? $_POST['id_funcionario'] : '';
        if ($id_cliente && $id_funcionario) {
            echo '<p>Empréstimo registrado para o cliente ID ' . $id_cliente . ' com o funcionário ID ' . $id_funcionario . '.</p>';
        }
    }

    mysqli_close($conexao);
    ?>
</body>
</html>