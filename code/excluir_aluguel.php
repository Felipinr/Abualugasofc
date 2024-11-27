<?php
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_aluguel = $_POST['id_aluguel'];

    if (empty($id_aluguel)) {
        echo "Erro: ID do aluguel não fornecido.";
        exit;
    }

    // Inicia uma transação
    $conexao->begin_transaction();

    try {
        // 1. Exclui os registros da tabela pagamentos
        $stmt = $conexao->prepare("DELETE FROM pagamentos WHERE id_aluguel = ?");
        $stmt->bind_param("i", $id_aluguel);
        $stmt->execute();
        $stmt->close();

        // 2. Exclui os registros da tabela alugueis_veiculos
        $stmt = $conexao->prepare("DELETE FROM alugueis_veiculos WHERE alugueis_id_aluguel = ?");
        $stmt->bind_param("i", $id_aluguel);
        $stmt->execute();
        $stmt->close();

        // 3. Exclui o registro da tabela alugueis
        $stmt = $conexao->prepare("DELETE FROM alugueis WHERE id_aluguel = ?");
        $stmt->bind_param("i", $id_aluguel);
        $stmt->execute();
        $stmt->close();

        // Confirma a transação
        $conexao->commit();
        echo "Aluguel excluído com sucesso!";
    } catch (Exception $e) {
        // Reverte a transação em caso de erro
        $conexao->rollback();
        echo "Erro ao excluir o aluguel: " . $e->getMessage();
    }
} else {
    echo "Método inválido.";
}
?>
