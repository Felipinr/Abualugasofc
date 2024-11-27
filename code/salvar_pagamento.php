<?php
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém os dados do formulário
    $id_aluguel = $_POST['id_aluguel'] ?? null;
    $data_pagamento = $_POST['data_pagamento'] ?? null;
    $valor_pagamento = $_POST['valor_pagamento'] ?? null;
    $metodo_pagamento = $_POST['metodo_pagamento'] ?? null;

    if ($id_aluguel && $data_pagamento && $valor_pagamento && $metodo_pagamento) {
        // Inicia uma transação
        $conexao->begin_transaction();

        try {
            // Insere o pagamento na tabela 'pagamentos'
            $stmt = $conexao->prepare("
                INSERT INTO pagamentos (id_aluguel, data_pagamento, valor_pagamento, metodo_pagamento) 
                VALUES (?, ?, ?, ?)
            ");
            $stmt->bind_param('isds', $id_aluguel, $data_pagamento, $valor_pagamento, $metodo_pagamento);
            $stmt->execute();
            $stmt->close();

            // Seleciona os IDs dos veículos associados ao aluguel
            $stmt = $conexao->prepare("
                SELECT veiculos_id_veiculo 
                FROM alugueis_veiculos 
                WHERE alugueis_id_aluguel = ?
            ");
            $stmt->bind_param('i', $id_aluguel);
            $stmt->execute();
            $result = $stmt->get_result();
            $veiculos = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();

            // Atualiza a disponibilidade de cada veículo para '1' (disponível)
            $stmt = $conexao->prepare("
                UPDATE veiculos 
                SET disponivel = 1 
                WHERE id_veiculo = ?
            ");
            foreach ($veiculos as $veiculo) {
                $stmt->bind_param('i', $veiculo['veiculos_id_veiculo']);
                $stmt->execute();
            }
            $stmt->close();

            // Confirma a transação
            $conexao->commit();

            echo "<div class='alert alert-success' role='alert'>
                Pagamento registrado com sucesso e veículos disponíveis para aluguel novamente!
            </div>";
            
            echo "<form action='deletar_aluguel.php' method='POST'>
            <input type='hidden' name='id_aluguel' value='$id_aluguel'>
            <button type='submit' name='excluir' class='btn btn-danger'>Clique aqui para finalizar o aluguel</button>
          </form>";
        } catch (Exception $e) {
            // Reverte a transação em caso de erro
            $conexao->rollback();
            echo "<div class='alert alert-danger' role='alert'>
                Erro ao registrar o pagamento: " . $e->getMessage() . "
            </div>";
        }
    } else {
        echo "<div class='alert alert-warning' role='alert'>
            Preencha todos os campos obrigatórios do formulário.
        </div>";
    }
} else {
    echo "<div class='alert alert-danger' role='alert'>
        Requisição inválida.
    </div>";
}
// Obtém os veículos associados ao aluguel
$stmt = $conexao->prepare("
    SELECT veiculos_id_veiculo, km_inicial
    FROM alugueis_veiculos 
    WHERE alugueis_id_aluguel = ?
");
$stmt->bind_param('i', $id_aluguel);
$stmt->execute();
$result = $stmt->get_result();
$veiculos = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

?>
