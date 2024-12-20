<?php
require_once 'conexao.php';

/**
 * Salva o pagamento de um aluguel e atualiza a disponibilidade do veículo.
 *
 * Este script é responsável por salvar os dados de pagamento de um aluguel no banco de dados
 * e atualizar a disponibilidade do veículo associado ao aluguel. O script valida os dados enviados
 * via POST e executa as operações de inserção e atualização no banco de dados dentro de uma transação.
 * 
 * O script segue os seguintes passos:
 * 1. Validação dos dados enviados via POST.
 * 2. Início de uma transação no banco de dados.
 * 3. Inserção dos dados de pagamento na tabela `pagamentos`.
 * 4. Recuperação dos veículos associados ao aluguel.
 * 5. Atualização da disponibilidade dos veículos para disponível.
 * 6. Commit da transação.
 * 7. Exibição de mensagens de sucesso ou erro.
 *
 * @param mysqli $conexao Conexão com o banco de dados.
 * @return void
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_aluguel = $_POST['id_aluguel'] ?? null;
    $data_pagamento = $_POST['data_pagamento'] ?? null;
    $valor_pagamento = $_POST['valor_pagamento'] ?? null;
    $metodo_pagamento = $_POST['metodo_pagamento'] ?? null;
    $km_final = $_POST['km_final'] ?? null;

   
    if ($id_aluguel && $data_pagamento && $valor_pagamento && $metodo_pagamento && $km_final) {
        $conexao->begin_transaction();

        try {
            $stmt = $conexao->prepare("
                INSERT INTO pagamentos (id_aluguel, data_pagamento, valor_pagamento, metodo_pagamento) 
                VALUES (?, ?, ?, ?)
            ");
            $stmt->bind_param('isds', $id_aluguel, $data_pagamento, $valor_pagamento, $metodo_pagamento);
            $stmt->execute();
            $stmt->close();

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

         $conexao->commit();


            echo "<div class='alert alert-success' role='alert'>
                Pagamento registrado com sucesso!
            </div>";

            echo "<form action='deletar_aluguel.php' method='POST'>
            <input type='hidden' name='id_aluguel' value='$id_aluguel'>
            <button type='submit' name='excluir' class='btn btn-danger'>Clique aqui para finalizar o aluguel</button>
          </form>";
        } catch (Exception $e) {
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
