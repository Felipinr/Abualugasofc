<?php
 require_once 'conexao.php'; 

/**
 * Processa o pagamento de alugueis de veículos.
 *
 * Este script é responsável por processar o pagamento de alugueis de veículos,
 * atualizando a quilometragem dos veículos e registrando o pagamento no banco de dados.
 * Caso o pagamento e as informações dos alugueis sejam processados com sucesso,
 * a transação é confirmada. Caso contrário, a transação é revertida.
 * 
 * O script segue os seguintes passos:
 * 1. Validação de dados de entrada.
 * 2. Cálculo do valor total do pagamento baseado nos quilômetros rodados.
 * 3. Atualização da quilometragem dos veículos.
 * 4. Marcação do aluguel como finalizado.
 * 5. Registro do pagamento na tabela de pagamentos.
 * 6. Confirmação ou reversão da transação.
 *
 * @param mysqli $conexao Conexão com o banco de dados.
 * @return void
 */

// Verifica se os dados necessários foram enviados via POST
if (!isset($_POST['metodo_pagamento'], $_POST['data_pagamento'], $_POST['km_final']) || empty($_POST['km_final'])) {
    // Exibe uma mensagem de erro caso algum dado necessário esteja ausente
    echo "<div class='alert alert-danger' role='alert'>Dados incompletos. Por favor, preencha todos os campos.</div>";
    exit;  // Finaliza a execução do script caso os dados não sejam válidos
}

// Atribui as variáveis com os valores recebidos do formulário
$metodo_pagamento = $_POST['metodo_pagamento'];
$data_pagamento = $_POST['data_pagamento'];
$km_finais = $_POST['km_final']; // Array com os km finais enviados
$total_pago = 0; // Inicializa a variável de total pago

try {
    // Inicia uma transação no banco de dados para garantir integridade
    mysqli_begin_transaction($conexao);

    // Loop sobre os alugueis para processar o pagamento
    foreach ($km_finais as $id_aluguel => $km_final) {
        // Consulta para buscar as informações do aluguel e do veículo
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

        // Prepara a consulta SQL e executa com o ID do aluguel
        $stmt = mysqli_prepare($conexao, $query);
        mysqli_stmt_bind_param($stmt, "i", $id_aluguel);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $veiculo_id, $valor_km, $km_inicial);
        mysqli_stmt_fetch($stmt);  // Busca o resultado
        mysqli_stmt_close($stmt);  // Fecha a execução da consulta

        // Verifica se as informações retornadas são válidas
        if (!$veiculo_id || !$valor_km || $km_final < $km_inicial) {
            // Caso os dados sejam inconsistentes, lança uma exceção
            throw new Exception("Dados inconsistentes para o aluguel ID $id_aluguel.");
        }

        // Calcula o valor a ser pago pelo aluguel com base na quilometragem
        $km_rodados = $km_final - $km_inicial;
        $subtotal = $km_rodados * $valor_km;
        $total_pago += $subtotal;  // Acumula o valor total do pagamento

        // Atualiza a quilometragem do veículo no banco de dados
        $update_km_query = "UPDATE veiculos SET km_atual = ? WHERE id_veiculo = ?";
        $update_stmt = mysqli_prepare($conexao, $update_km_query);
        mysqli_stmt_bind_param($update_stmt, "ii", $km_final, $veiculo_id);
        mysqli_stmt_execute($update_stmt);
        mysqli_stmt_close($update_stmt);

        // Marca o aluguel como finalizado, definindo a data de término
        $update_aluguel_query = "UPDATE alugueis SET data_fim = ? WHERE id_aluguel = ?";
        $update_aluguel_stmt = mysqli_prepare($conexao, $update_aluguel_query);
        mysqli_stmt_bind_param($update_aluguel_stmt, "si", $data_pagamento, $id_aluguel);
        mysqli_stmt_execute($update_aluguel_stmt);
        mysqli_stmt_close($update_aluguel_stmt);
    }

    // Insere o pagamento na tabela de pagamentos
    $insert_pagamento_query = "
        INSERT INTO pagamentos (metodo_pagamento, valor_pagamento, data_pagamento)
        VALUES (?, ?, ?)
    ";
    $insert_stmt = mysqli_prepare($conexao, $insert_pagamento_query);
    mysqli_stmt_bind_param($insert_stmt, "sds", $metodo_pagamento, $total_pago, $data_pagamento);
    mysqli_stmt_execute($insert_stmt);
    $id_pagamento = mysqli_insert_id($conexao);  // Obtém o ID do pagamento inserido
    mysqli_stmt_close($insert_stmt);  // Fecha a execução da inserção do pagamento

    // Finaliza a transação no banco de dados (commit)
    mysqli_commit($conexao);

    // Exibe uma mensagem de sucesso com o ID do pagamento gerado
    echo "<div class='alert alert-success' role='alert'>
            Pagamento registrado com sucesso! ID do pagamento: $id_pagamento
          </div>";
    echo "<a href='index.html' class='btn btn-primary'>Voltar ao Início</a>";

} catch (Exception $e) {
    // Em caso de erro, reverte a transação (rollback)
    mysqli_rollback($conexao);
    
    // Exibe a mensagem de erro e permite tentar novamente
    echo "<div class='alert alert-danger' role='alert'>
            Ocorreu um erro ao registrar o pagamento: {$e->getMessage()}
          </div>";
    echo "<a href='pagamento3.php' class='btn btn-secondary'>Tentar Novamente</a>";
}

// Fecha a conexão com o banco de dados
mysqli_close($conexao);
?>
