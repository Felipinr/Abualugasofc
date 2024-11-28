<?php
require_once 'conexao.php';

/**
 * Exclui um aluguel e seus registros associados.
 *
 * Este script é responsável por excluir um aluguel e todos os registros associados a ele
 * no banco de dados. O script valida se o ID do aluguel foi enviado via POST e, em caso afirmativo,
 * executa as operações de exclusão dentro de uma transação.
 * 
 * O script segue os seguintes passos:
 * 1. Validação do ID do aluguel enviado via POST.
 * 2. Início de uma transação no banco de dados.
 * 3. Exclusão dos registros de pagamento associados ao aluguel.
 * 4. Exclusão dos registros de veículos associados ao aluguel.
 * 5. Exclusão do registro do aluguel.
 * 6. Commit da transação.
 * 7. Exibição de mensagens de sucesso ou erro.
 *
 * @param mysqli $conexao Conexão com o banco de dados.
 * @param int    $id_aluguel ID do aluguel a ser excluído. Este valor é fornecido via parâmetro `$_POST['id_aluguel']`.
 * @param string $request_method Tipo da requisição HTTP. Verifica se a requisição é do tipo POST para realizar a exclusão.
 * @return void
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_aluguel = $_POST['id_aluguel'] ?? null;

    if ($id_aluguel) {
        $conexao->begin_transaction();

        try {
            $stmt = $conexao->prepare("DELETE FROM pagamentos WHERE id_aluguel = ?");
            $stmt->bind_param('i', $id_aluguel);
            $stmt->execute();
            $stmt->close();

            $stmt = $conexao->prepare("DELETE FROM alugueis_veiculos WHERE alugueis_id_aluguel = ?");
            $stmt->bind_param('i', $id_aluguel);
            $stmt->execute();
            $stmt->close();

            $stmt = $conexao->prepare("DELETE FROM alugueis WHERE id_aluguel = ?");
            $stmt->bind_param('i', $id_aluguel);
            $stmt->execute();
            $stmt->close();

            $conexao->commit();

            echo "<div class='alert alert-success' role='alert'>
                Clique no botão abaixo para voltar à tela inicial.
            </div>";

            echo "<form action='index.html' method='get'>
                    <button type='submit' class='btn btn-primary'>Voltar</button>
                  </form>";

        } catch (Exception $e) {
            $conexao->rollback();
            echo "<div class='alert alert-danger' role='alert'>
                Erro ao excluir o aluguel: " . $e->getMessage() . "
            </div>";
        }
    } else {
        echo "<div class='alert alert-warning' role='alert'>
            ID do aluguel não fornecido.
        </div>";
    }
} else {
    echo "<div class='alert alert-danger' role='alert'>
        Requisição inválida.
    </div>";
}
?>
