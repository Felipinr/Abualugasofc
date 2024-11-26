<?php
require_once 'conexao.php';

// Verifica se os dados necessários foram enviados
if (!isset($_POST['metodo_pagamento'], $_POST['data_pagamento'], $_POST['km_final']) || empty($_POST['km_final'])) {
    echo "<div class='alert alert-danger' role='alert'>Dados incompletos. Por favor, preencha todos os campos.</div>";
    exit;
}

$metodo_pagamento = $_POST['metodo_pagamento'];
$data_pagamento = $_POST['data_pagamento'];
$km_finais = $_POST['km_final']; // Array com os km finais enviados
$total_pago = 0;

try {
    // Inicia uma transação
    mysqli_begin_transaction($conexao);

    foreach ($km_finais as $id_aluguel => $km_final) {
        // Busca o aluguel relacionado
        $query = "
            SELECT 
                av.veiculos_id_veiculo,
                a.valor_km,
                v.km_atual
            FROM 
                alugueis_veiculos av
            INNER JOIN 
                alugueis a ON av.alugueis_id_aluguel = a.id_aluguel
            INNER JOIN 
                veiculos v ON av.veiculos_id_veiculo = v.id_veiculo
            WHERE 
                a.id_aluguel = ?
        ";

        $stmt = mysqli_prepare($conexao, $query);
        mysqli_stmt_bind_param($stmt, "i", $id_aluguel);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $id_veiculo, $valor_km, $km_inicial);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        if (!$id_veiculo || !$valor_km || $km_final < $km_inicial) {
            throw new Exception("Dados inconsistentes para o aluguel ID $id_aluguel.");
        }

        // Calcula o total do aluguel
        $km_rodados = $km_final - $km_inicial;
        $subtotal = $km_rodados * $valor_km;
        $total_pago += $subtotal;

        // Atualiza o km do veículo
        $update_km_query = "UPDATE veiculos SET km_atual = ? WHERE id_veiculo = ?";
        $update_stmt = mysqli_prepare($conexao, $update_km_query);
        mysqli_stmt_bind_param($update_stmt, "ii", $km_final, $veiculo_id);
        mysqli_stmt_execute($update_stmt);
        mysqli_stmt_close($update_stmt);

        // Marca o aluguel como finalizado
        $update_aluguel_query = "UPDATE alugueis SET data_fim = ? WHERE id_aluguel = ?";
        $update_aluguel_stmt = mysqli_prepare($conexao, $update_aluguel_query);
        mysqli_stmt_bind_param($update_aluguel_stmt, "si", $data_pagamento, $id_aluguel);
        mysqli_stmt_execute($update_aluguel_stmt);
        mysqli_stmt_close($update_aluguel_stmt);
    }

    // Insere o pagamento no banco de dados
    $insert_pagamento_query = "
        INSERT INTO pagamentos (metodo_pagamento, valor_pagamento, data_pagamento)
        VALUES (?, ?, ?)
    ";
    $insert_stmt = mysqli_prepare($conexao, $insert_pagamento_query);
    mysqli_stmt_bind_param($insert_stmt, "sds", $metodo_pagamento, $total_pago, $data_pagamento);
    mysqli_stmt_execute($insert_stmt);
    $id_pagamento = mysqli_insert_id($conexao);
    mysqli_stmt_close($insert_stmt);

    // Finaliza a transação
    mysqli_commit($conexao);

    echo "<div class='alert alert-success' role='alert'>
            Pagamento registrado com sucesso! ID do pagamento: $id_pagamento
          </div>";
    echo "<a href='index.html' class='btn btn-primary'>Voltar ao Início</a>";
} catch (Exception $e) {
    // Reverte a transação em caso de erro
    mysqli_rollback($conexao);
    echo "<div class='alert alert-danger' role='alert'>
            Ocorreu um erro ao registrar o pagamento: {$e->getMessage()}
          </div>";
    echo "<a href='pagamento3.php' class='btn btn-secondary'>Tentar Novamente</a>";
}

mysqli_close($conexao);
?>
