<?php
require_once 'conexao.php';

function kmInicialVeiculo($conexao, $id_veiculo) {
    $sql = "SELECT km_atual FROM veiculos WHERE id_veiculo = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id_veiculo);
    
    if (!mysqli_stmt_execute($stmt)) {
        die('Erro ao obter quilometragem inicial: ' . mysqli_stmt_error($stmt));
    }

    mysqli_stmt_bind_result($stmt, $km_inicial);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    return $km_inicial !== null ? $km_inicial : null;
}

function salvarEmprestimo($conexao, $idfuncionario, $idcliente, $data_inicio, $data_fim, $valor_km) {
    $sql = "INSERT INTO alugueis (id_funcionario, id_cliente, data_inicio, data_fim, valor_km) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'iissd', $idfuncionario, $idcliente, $data_inicio, $data_fim, $valor_km);
    
    if (!mysqli_stmt_execute($stmt)) {
        die('Erro na execução da consulta de aluguel: ' . mysqli_stmt_error($stmt));
    }

    $id = mysqli_insert_id($conexao);
    mysqli_stmt_close($stmt);

    return $id;
}

function salvarVeiculoEmprestimo($conexao, $id_aluguel, $id_veiculo) {
    $km_inicial = kmInicialVeiculo($conexao, $id_veiculo);
    if ($km_inicial === null) {
        die('Erro: quilometragem inicial não encontrada para o veículo ' . $id_veiculo);
    }
    $km_final = 0;

    $sql = "INSERT INTO alugueis_veiculos (alugueis_id_aluguel, veiculos_id_veiculo, km_inicial, km_final, id_veiculo) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexao, $sql);
    
    mysqli_stmt_bind_param($stmt, 'iisss', $id_aluguel, $id_veiculo, $km_inicial, $km_final, $id_veiculo);
    if (!mysqli_stmt_execute($stmt)) {
        die('Erro na execução da consulta de veículo: ' . mysqli_stmt_error($stmt));
    }

    mysqli_stmt_close($stmt);
}

if (!isset($_POST['id_funcionario'], $_POST['id_cliente'], $_POST['data_inicio'], $_POST['data_fim'], $_POST['valor_km'], $_POST['veiculos'])) {
    die('Dados do formulário incompletos.');
}

$id_funcionario = $_POST['id_funcionario'];
$id_cliente = $_POST['id_cliente'];
$data_inicio = $_POST['data_inicio'];
$data_fim = $_POST['data_fim'];
$valor_km = $_POST['valor_km'];

$id_aluguel = salvarEmprestimo($conexao, $id_funcionario, $id_cliente, $data_inicio, $data_fim, $valor_km);

$veiculos_array = explode(',', $_POST['veiculos']);
foreach ($veiculos_array as $veiculo) {
    $id_veiculo = intval($veiculo);
    if (empty($id_veiculo)) {
        continue; 
    }

    salvarVeiculoEmprestimo($conexao, $id_aluguel, $id_veiculo);

    $query_atualiza_veiculo = "UPDATE veiculos SET disponivel = 0 WHERE id_veiculo = ?";
    $stmt_atualiza = mysqli_prepare($conexao, $query_atualiza_veiculo);
    
    mysqli_stmt_bind_param($stmt_atualiza, 'i', $id_veiculo);
    if (!mysqli_stmt_execute($stmt_atualiza)) {
        die('Erro na execução da consulta de atualização de veículo: ' . mysqli_stmt_error($stmt_atualiza));
    }
}

mysqli_close($conexao);

// Estilizando a mensagem de sucesso e centralizando na página
echo "
<!DOCTYPE html>
<html lang='pt-BR'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Sucesso</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .message-box {
            background-color: #dff0d8;
            color: #3c763d;
            padding: 20px;
            border: 1px solid #d6e9c6;
            border-radius: 4px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .message-box h1 {
            margin: 0 0 10px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #337ab7;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .button:hover {
            background-color: #286090;
        }
    </style>
</head>
<body>

    <div class='message-box'>
        <h1>Aluguel realizado com sucesso!</h1>
        <a href='index.html' class='button'>Voltar à página principal</a>
    </div>

</body>
</html>
";
?>
