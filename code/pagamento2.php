<?php
require_once 'conexao.php';
require_once 'core.php';
require_once 'login2.php';

// Verifica se o id_cliente foi enviado via POST
if (isset($_POST['id_cliente']) && !empty($_POST['id_cliente'])) {
    $id_cliente = $_POST['id_cliente'];
    $emprestimos = listarEmprestimoCliente($conexao, $id_cliente);
    $quantidade = count($emprestimos);
} else {
    echo "<div class='alert alert-danger' role='alert'>Cliente não selecionado. Por favor, selecione um cliente.</div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selecionar Empréstimos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="/style.css" />
</head>

<body>

<div class="container mt-5">
    <h3 class="text-center mb-4">Selecionar Empréstimo</h3>

    <form action="pagamento2.php" method="GET">
        <?php
        if ($quantidade > 0) {
            echo "<div class='mb-3'>";
            echo "<label class='form-label'>Empréstimos:</label>";
            echo "<div class='list-group'>"; // Usando uma lista para exibir os carros

            // Itera sobre os empréstimos e exibe as informações dos carros
            foreach ($emprestimos as $emprestimo) {
                $modelo = $emprestimo['modelo'];
                $placa = $emprestimo['placa'];

                // Exibe os veículos em uma lista
                echo "<div class='list-group-item'>";
                echo "<strong>Modelo:</strong> $modelo <br>";
                echo "<strong>Placa:</strong> $placa <br>";
                echo "</div>";
            }

            echo "</div>"; // Fecha a lista de veículos
            echo "</div>";
            echo "<input type='hidden' name='id_cliente' value='$id_cliente'>";  
            echo "<div class='text-center'>";
            echo "</div>";
        } else {
            echo "<div class='alert alert-warning' role='alert'>Não há empréstimos para esse cliente.</div>";
        }
        ?>
    </form>
    <div class="text-center mt-4">
        <a href="pagamento3.php?id_cliente=<?php echo $id_cliente; ?>" class="btn btn-success">>>Próxima página<<</a>
    </div>
    <div class="text-center mt-4">
        <a href="pagamento.php" class="btn btn-secondary">Voltar</a>
        <a href="index.html" class="btn btn-secondary">Voltar ao início</a>
    </div>
</div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
