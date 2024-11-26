<?php
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_aluguel = $_POST['id_aluguel'] ?? null;
    $data_pagamento = $_POST['data_pagamento'] ?? null;
    $valor_pagamento = $_POST['valor_pagamento'] ?? null;
    $metodo_pagamento = $_POST['metodo_pagamento'] ?? null;

    // Validação básica dos dados
    if (!$id_aluguel || !$data_pagamento || !$valor_pagamento || !$metodo_pagamento) {
        echo "<div class='alert alert-danger' role='alert'>Todos os campos são obrigatórios.</div>";
        exit;
    }

    // Preparação da consulta
    $stmt = $conexao->prepare("INSERT INTO pagamentos (id_aluguel, data_pagamento, valor_pagamento, metodo_pagamento) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isds", $id_aluguel, $data_pagamento, $valor_pagamento, $metodo_pagamento);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success' role='alert'>Pagamento registrado com sucesso.</div>";
    } else {
        echo "<div class='alert alert-danger' role='alert'>Erro ao registrar o pagamento: " . $stmt->error . "</div>";
    }

    $stmt->close();
    $conexao->close();
}
?>
