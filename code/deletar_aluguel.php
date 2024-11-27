<?php
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_aluguel = $_POST['id_aluguel'] ?? null;

    if ($id_aluguel) {
        // Inicia uma transação
        $conexao->begin_transaction();

        try {
            // Excluir o pagamento relacionado ao aluguel antes de excluir o aluguel
            $stmt = $conexao->prepare("DELETE FROM pagamentos WHERE id_aluguel = ?");
            $stmt->bind_param('i', $id_aluguel);
            $stmt->execute();
            $stmt->close();

            // Excluir os registros da tabela 'alugueis_veiculos' relacionados ao aluguel
            $stmt = $conexao->prepare("DELETE FROM alugueis_veiculos WHERE alugueis_id_aluguel = ?");
            $stmt->bind_param('i', $id_aluguel);
            $stmt->execute();
            $stmt->close();

            // Excluir o aluguel da tabela 'alugueis'
            $stmt = $conexao->prepare("DELETE FROM alugueis WHERE id_aluguel = ?");
            $stmt->bind_param('i', $id_aluguel);
            $stmt->execute();
            $stmt->close();

            // Confirma a transação
            $conexao->commit();

            echo "<div class='alert alert-success' role='alert'>
                Clique no botão abaixo para voltar à tela inicial.
            </div>";

            // Botão para voltar à tela inicial
            echo "<form action='index.html' method='get'>
                    <button type='submit' class='btn btn-primary'>Voltar</button>
                  </form>";

        } catch (Exception $e) {
            // Reverte a transação em caso de erro
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
