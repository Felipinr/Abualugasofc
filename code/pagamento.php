<?php
require_once 'conexao.php';
require_once 'core.php';

// Verificar se o cliente foi selecionado
if (isset($_POST['id_cliente']) && !empty($_POST['id_cliente'])) {
    $id_cliente = $_POST['id_cliente'];
    header("Location: pagamento2.php?id_cliente=$id_cliente"); // Redireciona para a página pagamento2.php
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selecionar Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>

<div class="container mt-5">
    <h3 class="text-center mb-4">Selecione um Cliente</h3>
    
    <form action="pagamento2.php" method="POST">
        <div class="mb-3">
            <label for="id_cliente" class="form-label">Cliente:</label>
            <select name="id_cliente" id="id_cliente" class="form-select" required>
                <option value="">Selecione um cliente</option>
                <?php
                $query_clientes = "SELECT id_cliente, nome FROM clientes";
                $result_clientes = mysqli_query($conexao, $query_clientes);
                while ($row = mysqli_fetch_assoc($result_clientes)) {
                    echo "<option value='{$row['id_cliente']}'>{$row['nome']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="text-center">
            <input type="submit" value="Selecionar Cliente" class="btn btn-primary">
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
