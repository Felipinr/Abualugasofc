<?php

$valor = $_POST['valor'];
$preco_por_km = $_POST['preco_por_km'];
$data_pagamento = $_POST['data_pagamento'];
$metodo = $_POST['metodo'];
$id_aluguel = $_POST['id_aluguel'];

require_once 'conexao.php';

// Etapas do pagamento

// Grava o pagamento
$sql = "INSERT INTO tb_pagamento (valor, preco_por_km, data_pagamento, metodo, tb_aluguel_id_aluguel) VALUES (?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, "dsssi", $valor, $preco_por_km, $data_pagamento, $metodo, $id_aluguel);
mysqli_stmt_execute($stmt);

// Atualiza o status dos veículos
$sqlUpdateVeiculo = "UPDATE tb_veiculo SET status = 'Disponível' WHERE id_veiculo IN (SELECT id_veiculo FROM tb_aluguel_has_tb_veiculo WHERE tb_aluguel_id_aluguel = ?)";
$stmtUpdateVeiculo = mysqli_prepare($conexao, $sqlUpdateVeiculo);
mysqli_stmt_bind_param($stmtUpdateVeiculo, "i", $id_aluguel);
mysqli_stmt_execute($stmtUpdateVeiculo);

// Atualiza o status que aparece na tabela aluguel
$sqlUpdateAluguel = "UPDATE tb_aluguel SET status = 'Pago' WHERE id_aluguel = ?";
$stmtUpdateAluguel = mysqli_prepare($conexao, $sqlUpdateAluguel);
mysqli_stmt_bind_param($stmtUpdateAluguel, "i", $id_aluguel);
mysqli_stmt_execute($stmtUpdateAluguel);
mysqli_stmt_close($stmtUpdateAluguel);

// Atualiza a quilometragem dos veículos
foreach ($_POST['id_veiculo'] as $index => $idVeiculo) {
    $kmPercorrido = $_POST['kmpercorrido'][$index];

    // Atualiza cada veículo somando o km atual com o percorrido
    $sqlUpdateKmVeiculo = "UPDATE tb_veiculo SET km_veiculo = km_veiculo + ? WHERE id_veiculo = ?";
    $stmtUpdateKmVeiculo = mysqli_prepare($conexao, $sqlUpdateKmVeiculo);
    mysqli_stmt_bind_param($stmtUpdateKmVeiculo, "di", $kmPercorrido, $idVeiculo);
    mysqli_stmt_execute($stmtUpdateKmVeiculo);
    mysqli_stmt_close($stmtUpdateKmVeiculo);
}

// Exclui o registro da tabela tb_aluguel_has_tb_veiculo
$sqlDeleteVeiculoAlugado = "DELETE FROM tb_aluguel_has_tb_veiculo WHERE tb_aluguel_id_aluguel = ?";
$stmtDeleteVeiculoAlugado = mysqli_prepare($conexao, $sqlDeleteVeiculoAlugado);
mysqli_stmt_bind_param($stmtDeleteVeiculoAlugado, "i", $id_aluguel);
mysqli_stmt_execute($stmtDeleteVeiculoAlugado);

mysqli_stmt_close($stmt);
mysqli_close($conexao);
header('Location: success.php');