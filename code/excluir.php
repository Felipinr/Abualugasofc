<?php
require_once "conexao.php";

if (!isset($_GET['id'])) {
    header("Location: listar_carros.php");
    exit();
}

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Preparar e executar a exclusão
    $sql = "DELETE FROM veiculos WHERE id_veiculo = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Redirecionar para a lista de carros após a exclusão
    header("Location: listar_carros.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Carro</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Excluir Carro</h1>
        <form method="post">
            <div class="alert alert-danger">
                Tem certeza que deseja excluir este carro? Esta ação não pode ser desfeita.
            </div>
            <button type="submit" class="btn btn-danger">Excluir</button>
            <a href="listar_carros.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>