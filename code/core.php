<?php

function listarCarros($conexao)
{
    $sql = "SELECT * FROM veiculos";

    $stmt = mysqli_prepare($conexao, $sql);

    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result($stmt, $id_veiculo, $modelo, $marca, $ano, $placa, $cor, $km_atual, $tipo, $airbag, $num_bancos, $num_portas, $combustivel, $cambio, $ar_coondicionado, $direcao, $som, $bluetooth, $gps, $sensor_estacionamento, $camera_re, $disponivel);

    mysqli_stmt_store_result($stmt);

    $lista = [];
    if (mysqli_stmt_num_rows($stmt) > 0) {
        while (mysqli_stmt_fetch($stmt)) {
            // $situacao = buscarNomeSituacaoPorId($conexao, $id_veiculo);

            $lista[] = [$id_veiculo, $modelo, $marca, $ano, $placa, $cor, $km_atual, $tipo, $airbag, $num_bancos, $num_portas, $combustivel, $cambio, $ar_coondicionado, $direcao, $som, $bluetooth, $gps, $sensor_estacionamento, $camera_re, $disponivel];
        }
    }

    mysqli_stmt_close($stmt);
    // echo $lista;
    return $lista;
}

// function listarSituacoes($conexao)
// {
//    $sql = "SELECT * FROM veiculos";
//
//    $stmt = mysqli_prepare($conexao, $sql);
//
//  mysqli_stmt_execute($stmt);
//
//    mysqli_stmt_bind_result($stmt, $id_veiculo, $modelo);
//
//    mysqli_stmt_store_result($stmt);
//
//    $lista = [];
//    if (mysqli_stmt_num_rows($stmt) > 0) {
//        while (mysqli_stmt_fetch($stmt)) {
//            $lista[] = [$id_veiculo, $modelo];
//        }
//    }
//    mysqli_stmt_close($stmt);
//
//   return $lista;
//}

function buscarNomeSituacaoPorId($conexao, $id_veiculo)
{
     $sql = "SELECT id_veiculo FROM veiculos WHERE id_veiculo = ?";

    $stmt = mysqli_prepare($conexao, $sql);

   mysqli_stmt_bind_param($stmt, "i", $id_veiculo);

   mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result($stmt, $modelo);

    if (mysqli_stmt_fetch($stmt)) {
        return $modelo;
     }

     mysqli_stmt_close($stmt);
     }






function salvarCliente($conexao, $nome) {
    $sql = "INSERT INTO cliente (nome) VALUES (?)";
    $stmt = mysqli_prepare($conexao, $sql);

    mysqli_stmt_bind_param($stmt, "s", $nome);
    mysqli_stmt_execute($stmt);

    $id = mysqli_stmt_insert_id($stmt);
    mysqli_stmt_close($stmt);

    return $id;
}



function salvarFuncionario($conexao, $nome) {
    $sql = "INSERT INTO funcionario (nome) VALUES (?)";
    $stmt = mysqli_prepare($conexao, $sql);

    mysqli_stmt_bind_param($stmt, "s", $nome);
    mysqli_stmt_execute($stmt);

    $id = mysqli_stmt_insert_id($stmt);
    mysqli_stmt_close($stmt);

    return $id;
}

function salvarVeiculo($conexao, $km, $marca, $modelo) {
    $sql = "INSERT INTO veiculo (km_atual, marca, modelo) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conexao, $sql);

    mysqli_stmt_bind_param($stmt, "sss", $km, $marca, $modelo);
    mysqli_stmt_execute($stmt);

    $id = mysqli_stmt_insert_id($stmt);
    mysqli_stmt_close($stmt);

    return $id;
}

function salvarEmprestimo($conexao, $idfuncionario, $idcliente) {
    $sql = "INSERT INTO emprestimo (idfuncionario, idcliente) VALUES (?, ?)";
    $stmt = mysqli_prepare($conexao, $sql);

    mysqli_stmt_bind_param($stmt, "ii", $idfuncionario, $idcliente);
    mysqli_stmt_execute($stmt);

    $id = mysqli_stmt_insert_id($stmt);
    mysqli_stmt_close($stmt);

    return $id;
}

function salvarVeiculoEmprestimo($conexao, $idemprestimo, $idveiculo) {

    $km_inicial = kmInicialVeiculo($conexao, $idveiculo);
    $km_final = 0;

    $sql = "INSERT INTO emprestimo_has_veiculo (idemprestimo, idveiculo, km_inicial, km_final) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexao, $sql);

    mysqli_stmt_bind_param($stmt, "iiss", $idemprestimo, $idveiculo, $km_inicial, $km_final);
    mysqli_stmt_execute($stmt);

    $id = mysqli_stmt_insert_id($stmt);
    mysqli_stmt_close($stmt);

    return $id;
}

function kmInicialVeiculo($conexao, $idveiculo) {
    $sql = "SELECT km_atual FROM veiculo WHERE idveiculo = ?";
    $stmt = mysqli_prepare($conexao, $sql);

    mysqli_stmt_bind_param($stmt, 'i', $idveiculo);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result($stmt, $km);

    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    return $km;
}

function efetuarPagamento($conexao, $idemprestimo, $valor, $precokm, $metodo) {
    $sql = "INSERT INTO pagamento (idemprestimo, valor, preco_por_km, metodo) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexao, $sql);

    mysqli_stmt_bind_param($stmt, "idds", $idemprestimo, $valor, $precokm, $metodo);
    mysqli_stmt_execute($stmt);

    $id = mysqli_stmt_insert_id($stmt);
    mysqli_stmt_close($stmt);

    return $id;
}

// UPDATE emprestimo_has_veiculo SET km_final = 50000 WHERE idemprestimo = 1 AND idveiculo = 1;

function atualiza_km_final($conexao, $km_final, $idemprestimo, $idveiculo) {
    $sql = "UPDATE emprestimo_has_veiculo SET km_final = ? WHERE idemprestimo = ? AND idveiculo = ?";
    $stmt = mysqli_prepare($conexao, $sql);

    mysqli_stmt_bind_param($stmt, "dii", $km_final, $idemprestimo, $idveiculo);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    atualiza_km_atual($conexao, $km_final, $idveiculo);
}

// UPDATE veiculo SET km_atual = 999 WHERE idveiculo = 3;
function atualiza_km_atual($conexao, $km_atual, $idveiculo) {
    $sql = "UPDATE veiculo SET km_atual = ? WHERE idveiculo = ?";
    $stmt = mysqli_prepare($conexao, $sql);

    mysqli_stmt_bind_param($stmt, "ii", $km_atual, $idveiculo);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
}
?>