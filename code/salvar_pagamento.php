<?php
require_once 'conexao.php';

// Verifica se os dados necessários foram enviados
if (!isset($_POST['metodo_pagamento'], $_POST['data_pagamento'], $_POST['km_final']) || empty($_POST['km_final'])) {
    echo "<div class='alert alert-danger' role='alert'>Dados incompletos. Por favor, preencha todos os campos.</div>";
    exit;
}

$metodo_pagamento = $_POST['metodo_pagamento'];
$data_pagamento = $_POST['data_pagamento'];
$km_finais = $_POST['km_final']; // Estrutura: [id_veiculo => km_final]
$total_pago = 0;

// Inicia a transação
mysqli_begin_transaction($conexao);

foreach ($km_finais as $id_veiculo => $km_final) {
    // Consulta para obter dados do veículo e o km_final associado ao aluguel
    $query = "
        SELECT 
            v.km_atual,
            a.valor_km,
            av.alugueis_id_aluguel,
            av.km_final -- Aqui estamos buscando o km_final da tabela alugueis_veiculos
        FROM 
            veiculos v
        INNER JOIN 
            alugueis_veiculos av ON v.id_veiculo = av.veiculos_id_veiculo
        INNER JOIN 
            alugueis a ON av.alugueis_id_aluguel = a.id_aluguel
        WHERE 
            v.id_veiculo = ?
    ";

    $stmt = mysqli_prepare($conexao, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_veiculo);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $km_inicial, $valor_km, $id_aluguel, $km_final_veiculo);

    if (mysqli_stmt_fetch($stmt)) {
        // Valida o Km final
        if ($km_final < $km_inicial) {
            mysqli_stmt_close($stmt);
            mysqli_rollback($conexao);
            echo "<div class='alert alert-danger' role='alert'>
                    O Km final para o veículo ID $id_veiculo é menor que o Km inicial. 
                    Km Inicial: $km_inicial, Km Final: $km_final.
                  </div>";
            exit;
        }

        // Calcula o valor para este veículo
        $km_rodados = $km_final - $km_inicial;
        $subtotal = $km_rodados * $valor_km;
        $total_pago += $subtotal;

        // Atualiza a quilometragem do veículo com o km_final da tabela alugueis_veiculos
        $update_km_query = "UPDATE veiculos SET km_atual = ? WHERE id_veiculo = ?";
        $update_stmt = mysqli_prepare($conexao, $update_km_query);
        mysqli_stmt_bind_param($update_stmt, "ii", $km_final, $id_veiculo); // Atualiza com o km_final recebido
        mysqli_stmt_execute($update_stmt);
        mysqli_stmt_close($update_stmt);

        // Atualiza a data de finalização do aluguel
        $update_aluguel_query = "UPDATE alugueis SET data_fim = ? WHERE id_aluguel = ?";
        $update_aluguel_stmt = mysqli_prepare($conexao, $update_aluguel_query);
        mysqli_stmt_bind_param($update_aluguel_stmt, "si", $data_pagamento, $id_aluguel);
        mysqli_stmt_execute($update_aluguel_stmt);
        mysqli_stmt_close($update_aluguel_stmt);
    } else {
        mysqli_stmt_close($stmt);
        mysqli_rollback($conexao);
        echo "<div class='alert alert-danger' role='alert'>
                Veículo ID $id_veiculo não encontrado ou não associado a um aluguel válido.
              </div>";
        exit;
    }

    mysqli_stmt_close($stmt);
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

// Confirma o sucesso
echo "<div class='alert alert-success' role='alert'>
        Pagamento registrado com sucesso! ID do pagamento: $id_pagamento
      </div>";
echo "<a href='index.html' class='btn btn-primary'>Voltar ao Início</a>";

mysqli_close($conexao);
?>
