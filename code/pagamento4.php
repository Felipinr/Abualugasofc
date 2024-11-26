<?php
require_once 'conexao.php'; 

/**
 * Registra o pagamento de um aluguel e realiza atualizações associadas.
 *
 * Este script realiza o registro do pagamento de um aluguel, atualizando as tabelas 
 * de pagamento, veículo e aluguel. Além disso, ele executa as seguintes ações:
 * 1. Registra os dados do pagamento na tabela `tb_pagamento`.
 * 2. Atualiza o status do veículo para "Disponível", indicando que foi devolvido.
 * 3. Marca o aluguel como "Pago".
 * 4. Atualiza a quilometragem dos veículos com base no valor percorrido informado.
 * 5. Exclui o vínculo entre o aluguel e os veículos da tabela `tb_aluguel_has_tb_veiculo`.
 *
 * @param float      $valor              O valor pago pelo aluguel.
 * @param float      $preco_por_km       O preço por quilômetro percorrido durante o aluguel.
 * @param string     $data_pagamento     A data em que o pagamento foi realizado.
 * @param string     $metodo             O método de pagamento utilizado (por exemplo, "Cartão", "Dinheiro").
 * @param int        $id_aluguel         O ID do aluguel que está sendo pago.
 * @param array      $id_veiculo         Um array de IDs dos veículos associados ao aluguel.
 * @param array      $kmpercorrido       Um array com a quilometragem percorrida de cada veículo.
 * @return void
 */

$valor = $_POST['valor'];  // Valor total pago pelo aluguel.
$preco_por_km = $_POST['preco_por_km'];  // Preço por quilômetro rodado.
$data_pagamento = $_POST['data_pagamento'];  // Data do pagamento.
$metodo = $_POST['metodo'];  // Método de pagamento utilizado.
$id_aluguel = $_POST['id_aluguel'];  // ID do aluguel que está sendo pago.



try {
    // Insere o pagamento realizado na tabela de pagamentos
    $sql = "INSERT INTO tb_pagamento (valor, preco_por_km, data_pagamento, metodo, tb_aluguel_id_aluguel) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, "dsssi", $valor, $preco_por_km, $data_pagamento, $metodo, $id_aluguel);
    mysqli_stmt_execute($stmt);

    // Atualiza o status dos veículos para "Disponível" após a devolução
    $sqlUpdateVeiculo = "UPDATE tb_veiculo SET status = 'Disponível' 
                         WHERE id_veiculo IN (SELECT id_veiculo FROM tb_aluguel_has_tb_veiculo 
                         WHERE tb_aluguel_id_aluguel = ?)";
    $stmtUpdateVeiculo = mysqli_prepare($conexao, $sqlUpdateVeiculo);
    mysqli_stmt_bind_param($stmtUpdateVeiculo, "i", $id_aluguel);
    mysqli_stmt_execute($stmtUpdateVeiculo);

    // Marca o aluguel como "Pago"
    $sqlUpdateAluguel = "UPDATE tb_aluguel SET status = 'Pago' WHERE id_aluguel = ?";
    $stmtUpdateAluguel = mysqli_prepare($conexao, $sqlUpdateAluguel);
    mysqli_stmt_bind_param($stmtUpdateAluguel, "i", $id_aluguel);
    mysqli_stmt_execute($stmtUpdateAluguel);
    mysqli_stmt_close($stmtUpdateAluguel);

    // Atualiza a quilometragem dos veículos
    foreach ($_POST['id_veiculo'] as $index => $idVeiculo) {
        $kmPercorrido = $_POST['kmpercorrido'][$index];

        // Atualiza a quilometragem total dos veículos somando os km percorridos
        $sqlUpdateKmVeiculo = "UPDATE tb_veiculo SET km_veiculo = km_veiculo + ? WHERE id_veiculo = ?";
        $stmtUpdateKmVeiculo = mysqli_prepare($conexao, $sqlUpdateKmVeiculo);
        mysqli_stmt_bind_param($stmtUpdateKmVeiculo, "di", $kmPercorrido, $idVeiculo);
        mysqli_stmt_execute($stmtUpdateKmVeiculo);
        mysqli_stmt_close($stmtUpdateKmVeiculo);
    }

    // Exclui o vínculo dos veículos com o aluguel da tabela `tb_aluguel_has_tb_veiculo`
    $sqlDeleteVeiculoAlugado = "DELETE FROM tb_aluguel_has_tb_veiculo WHERE tb_aluguel_id_aluguel = ?";
    $stmtDeleteVeiculoAlugado = mysqli_prepare($conexao, $sqlDeleteVeiculoAlugado);
    mysqli_stmt_bind_param($stmtDeleteVeiculoAlugado, "i", $id_aluguel);
    mysqli_stmt_execute($stmtDeleteVeiculoAlugado);

    // Fecha as instruções preparadas
    mysqli_stmt_close($stmt);
    mysqli_close($conexao);  // Fecha a conexão com o banco de dados.

    // Redireciona para a página de sucesso
    header('Location: success.php');
} catch (Exception $e) {
    // Em caso de erro, retorna um erro ao usuário
    echo "Erro ao processar o pagamento: " . $e->getMessage();
}
