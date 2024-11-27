<?php
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_aluguel = $_POST['id_aluguel'];
    $metodo_pagamento = $_POST['metodo_pagamento'];
    $data_pagamento = $_POST['data_pagamento'];
    $valor_pagamento = $_POST['valor_pagamento'];

    // Verifica se os dados necessários foram enviados
    if (empty($id_aluguel) || empty($metodo_pagamento) || empty($data_pagamento) || empty($valor_pagamento)) {
        echo "Erro: Falta informações necessárias.";
        exit;
    }

    // Inserir o pagamento na tabela pagamentos
    $stmt = $conexao->prepare("INSERT INTO pagamentos (id_aluguel, metodo_pagamento, data_pagamento, valor_pagamento) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("issd", $id_aluguel, $metodo_pagamento, $data_pagamento, $valor_pagamento);
    
    if ($stmt->execute()) {
        echo "Pagamento registrado com sucesso!<br>";

        // Mostrar o botão de excluir apenas se o pagamento for realizado
        echo "<form action='excluir_aluguel.php' method='POST'>
                <input type='hidden' name='id_aluguel' value='$id_aluguel'>
                <button type='submit' class='btn btn-danger'>Excluir Aluguel</button>
              </form>";
    } else {
        echo "Erro ao registrar o pagamento: " . $stmt->error;
    }
    
    $stmt->close();
}
?>
