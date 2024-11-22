<?php
require_once 'conexao.php';
require_once('TCPDF-main/tcpdf.php');
require_once 'core.php';

$id_funcionario = $_POST['id_funcionario'];
$id_cliente = $_POST['id_cliente'];
$veiculos = explode(',', $_POST['veiculos']);

$data_inicio = $_POST['data_inicio'];
$data_fim = $_POST['data_fim'];
$valor_km = $_POST['valor_km'];


$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);

$pdf->Cell(0, 10, 'Detalhes do Aluguel', 0, 1, 'C');
$pdf->Ln(5);
$pdf->Cell(40, 10, 'ID Funcionario:', 0, 0);
$pdf->Cell(0, 10, $id_funcionario, 0, 1);
$pdf->Cell(40, 10, 'ID Cliente:', 0, 0);
$pdf->Cell(0, 10, $id_cliente, 0, 1);
$pdf->Cell(40, 10, 'Veículos Alugados:', 0, 0);
$pdf->Cell(0, 10, implode(', ', $veiculos), 0, 1);
$pdf->Cell(40, 10, 'Data de Início:', 0, 0);
$pdf->Cell(0, 10, $data_inicio, 0, 1);
$pdf->Cell(40, 10, 'Data de Entrega:', 0, 0);
$pdf->Cell(0, 10, $data_fim, 0, 1);
$pdf->Cell(40, 10, 'Valor do KM Rodado:', 0, 0);
$pdf->Cell(0, 10, 'R$ ' . number_format($valor_km, 2, ',', '.'), 0, 1);

$pdf->Output('detalhes_aluguel.pdf', 'I');

/**
 * Obtém a quilometragem inicial de um veículo.
 *
 * @param mysqli $conexao Conexão ativa com o banco de dados.
 * @param int $id_veiculo ID do veículo.
 * @return int|null       Quilometragem inicial do veículo ou null se não encontrada.
 */


$incicial_km = kmInicialVeiculo($conexao, $idveiculo);
    
    if (!mysqli_stmt_execute($stmt)) {
        die('Erro ao obter quilometragem inicial: ' . mysqli_stmt_error($stmt));
    

    mysqli_stmt_bind_result($stmt, $km_inicial);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    return $km_inicial !== null ? $km_inicial : null;
    }

/**
 * Salva um novo empréstimo no banco de dados.
 *
 * @param mysqli $conexao       Conexão ativa com o banco de dados.
 * @param int $idfuncionario    ID do funcionário.
 * @param int $idcliente        ID do cliente.
 * @param string $data_inicio   Data de início do aluguel.
 * @param string $data_fim      Data de fim do aluguel.
 * @param float $valor_km       Valor por quilômetro.
 * @return int                  ID do empréstimo inserido.
 */

 $emprestimosalva = salvarEmprestimo($conexao, $idfuncionario, $idcliente);
 if (!mysqli_stmt_execute($stmt)) {
    die('Erro na execução da consulta de aluguel: ' . mysqli_stmt_error($stmt));


$id = mysqli_insert_id($conexao);
mysqli_stmt_close($stmt);

return $id;
}


/**
 * Salva a relação de um veículo com um empréstimo no banco de dados.
 *
 * @param mysqli $conexao Conexão ativa com o banco de dados.
 * @param int $id_aluguel ID do aluguel.
 * @param int $id_veiculo ID do veículo.
 */

$salvoemprestimoveic = salvarVeiculoEmprestimo($conexao, $idemprestimo, $idveiculo);

$km_inicial = kmInicialVeiculo($conexao, $id_veiculo);
if ($km_inicial === null) {
    die('Erro: quilometragem inicial não encontrada para o veículo ' . $id_veiculo);
}

    $km_final = 0;

    $sql = "INSERT INTO alugueis_veiculos (alugueis_id_aluguel, veiculos_id_veiculo, km_inicial, km_final) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexao, $sql);
    
    mysqli_stmt_bind_param($stmt, 'iiss', $id_aluguel, $id_veiculo, $km_inicial, $km_final);
    if (!mysqli_stmt_execute($stmt)) {
        die('Erro na execução da consulta de veículo: ' . mysqli_stmt_error($stmt));
    }

    mysqli_stmt_close($stmt);

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
