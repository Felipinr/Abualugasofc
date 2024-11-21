<?php
require_once "conexao.php";

/**
 * Função para editar as informações de um carro.
 *
 * Esta função recebe os dados do formulário para editar as informações de um carro na base de dados.
 * Quando o formulário é enviado, as informações do carro são atualizadas no banco de dados com base no ID do veículo.
 *
 * @param mysqli    $conexao    Conexão com o banco de dados.
 * @param int       $id         ID do veículo a ser editado.
 * @param string    $modelo     Modelo do carro.
 * @param string    $marca      Marca do carro.
 * @param int       $ano        Ano de fabricação do carro.
 * @param string    $placa      Placa do carro.
 * @param string    $cor        Cor do carro.
 * @param int       $km_atual   Quilometragem atual do carro.
 * @param string    $tipo       Tipo de carro (ex: sedan, SUV, etc.).
 * @return void
 */
function editarCarro($conexao, $id, $modelo, $marca, $ano, $placa, $cor, $km_atual, $tipo) {
    // Prepara a consulta SQL para atualizar os dados do carro.
    $sql = "UPDATE veiculos SET modelo = ?, marca = ?, ano = ?, placa = ?, cor = ?, km_atual = ?, modelo = ? WHERE id_veiculo = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    
    // Verifica se a preparação da consulta foi bem-sucedida.
    if (!$stmt) {
        die('Erro na preparação da consulta: ' . mysqli_error($conexao));
    }
    
    // Liga os parâmetros e executa a consulta.
    mysqli_stmt_bind_param($stmt, "sssssisi", $modelo, $marca, $ano, $placa, $cor, $km_atual, $tipo, $id);
    mysqli_stmt_execute($stmt);
    
    // Fecha o statement.
    mysqli_stmt_close($stmt);
}

/**
 * Função para carregar as informações de um carro baseado no ID.
 *
 * Esta função retorna as informações de um carro específico a partir de seu ID. Ela é usada para preencher os campos do formulário de edição.
 *
 * @param mysqli $conexao Conexão com o banco de dados.
 * @param int $id ID do veículo a ser carregado.
 * @return array|false Retorna um array com os dados do veículo ou false em caso de erro.
 */

function carregarCarro($conexao, $id) {
    // Prepara a consulta SQL para obter os dados do carro.
    $sql = "SELECT * FROM veiculos WHERE id_veiculo = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    
    // Verifica se a preparação da consulta foi bem-sucedida.
    if (!$stmt) {
        die('Erro na preparação da consulta: ' . mysqli_error($conexao));
    }
    
    // Liga o parâmetro e executa a consulta.
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    
    // Faz o fetch dos dados do veículo.
    $result = mysqli_stmt_get_result($stmt);
    
    // Verifica se foi encontrado algum veículo com o ID fornecido.
    if ($row = mysqli_fetch_assoc($result)) {
        mysqli_stmt_close($stmt);
        return $row; // Retorna os dados do veículo.
    } else {
        mysqli_stmt_close($stmt);
        return false; // Retorna false caso não encontre o veículo.
    }
}

// Verifica se o ID foi passado na URL para edição.
if (!isset($_GET['id'])) {
    header("Location: listar_carros.php");
    exit();
}

$id = $_GET['id'];

// Processa o formulário de edição.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $modelo = $_POST['modelo'];
    $marca = $_POST['marca'];
    $ano = $_POST['ano'];
    $placa = $_POST['placa'];
    $cor = $_POST['cor'];
    $km_atual = $_POST['km_atual'];
    $tipo = $_POST['tipo'];

    // Chama a função para editar o carro.
    editarCarro($conexao, $id, $modelo, $marca, $ano, $placa, $cor, $km_atual, $tipo);

    // Redireciona de volta para a lista de carros após a edição.
    header("Location: listar_carros.php");
    exit();
} else {
    // Carrega os dados do carro para preencher o formulário de edição.
    $carro = carregarCarro($conexao, $id);
    
    // Se o carro não for encontrado, redireciona para a lista de carros.
    if (!$carro) {
        header("Location: listar_carros.php");
        exit();
    }

    // Extrai os dados do carro para preencher os campos do formulário.
    $modelo = $carro['modelo'];
    $marca = $carro['marca'];
    $ano = $carro['ano'];
    $placa = $carro['placa'];
    $cor = $carro['cor'];
    $km_atual = $carro['km_atual'];
    $tipo = $carro['modelo'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Carro</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Editar Carro</h1>
        <form method="post">
            <div class="form-group">
                <label for="modelo">Modelo:</label>
                <input type="text" class="form-control" id="modelo" name="modelo" value="<?php echo htmlspecialchars($modelo); ?>" required>
            </div>
            <div class="form-group">
                <label for="marca">Marca:</label>
                <input type="text" class="form-control" id="marca" name="marca" value="<?php echo htmlspecialchars($marca); ?>" required>
            </div>
            <div class="form-group">
                <label for="ano">Ano:</label>
                <input type="number" class="form-control" id="ano" name="ano" value="<?php echo htmlspecialchars($ano); ?>" required>
            </div>
            <div class="form-group">
                <label for="placa">Placa:</label>
                <input type="text" class="form-control" id="placa" name="placa" value="<?php echo htmlspecialchars($placa); ?>" required>
            </div>
            <div class="form-group">
                <label for="cor">Cor:</label>
                <input type="text" class="form-control" id="cor" name="cor" value="<?php echo htmlspecialchars($cor); ?>" required>
            </div>
            <div class="form-group">
                <label for="km_atual">Km Atual:</label>
                <input type="number" class="form-control" id="km_atual" name="km_atual" value="<?php echo htmlspecialchars($km_atual); ?>" required>
            </div>
            <div class="form-group">
                <label for="tipo">Tipo:</label>
                <input type="text" class="form-control" id="tipo" name="tipo" value="<?php echo htmlspecialchars($tipo); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Salvar</button>
            <a href="listar_carros.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
