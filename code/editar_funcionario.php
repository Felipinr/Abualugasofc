<?php
require_once "conexao.php";

/**
 * Função para editar as informações de um funcionário.
 *
 * Esta função recebe os dados do formulário para editar as informações de um funcionário na base de dados.
 * Quando o formulário é enviado, as informações do funcionário são atualizadas no banco de dados com base no ID do funcionário.
 *
 * @param mysqli    $conexao    Conexão com o banco de dados.
 * @param int       $id         ID do funcionário a ser editado.
 * @param string    $nome       Nome do funcionário.
 * @param string    $cpf        CPF do funcionário.
 * @param string    $telefone   Telefone do funcionário.
 * @param string    $email      E-mail do funcionário.
 * @return void
 */
function editarFuncionario($conexao, $id, $nome, $cpf, $telefone, $email) {
    $sql = "UPDATE funcionarios SET nome = ?, cpf = ?, telefone = ?, email = ? WHERE id_funcionario = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    
    if (!$stmt) {
        die('Erro na preparação da consulta: ' . mysqli_error($conexao));
    }
    
    mysqli_stmt_bind_param($stmt, "ssssi", $nome, $cpf, $telefone, $email, $id);
    mysqli_stmt_execute($stmt);
    
    mysqli_stmt_close($stmt);
}

/**
 * Função para carregar as informações de um funcionário baseado no ID.
 *
 * Esta função retorna as informações de um funcionário específico a partir de seu ID. Ela é usada para preencher os campos do formulário de edição.
 *
 * @param mysqli $conexao   Conexão com o banco de dados.
 * @param int    $id        ID do funcionário a ser carregado.
 * @return array|false      Retorna um array com os dados do funcionário ou false em caso de erro.
 */
function carregarFuncionario($conexao, $id) {
    $sql = "SELECT * FROM funcionarios WHERE id_funcionario = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    
    if (!$stmt) {
        die('Erro na preparação da consulta: ' . mysqli_error($conexao));
    }
    
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        mysqli_stmt_close($stmt);
        return $row; 
    } else {
        mysqli_stmt_close($stmt);
        return false;
    }
}

if (!isset($_GET['id'])) {
    header("Location: listar_funcionarios.php");
    exit();
}

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];

    editarFuncionario($conexao, $id, $nome, $cpf, $telefone, $email);

    header("Location: listar_funcionarios.php");
    exit();
} else {
    $funcionario = carregarFuncionario($conexao, $id);
    
    if (!$funcionario) {
        header("Location: listar_funcionarios.php");
        exit();
    }

    $nome = $funcionario['nome'];
    $cpf = $funcionario['cpf'];
    $telefone = $funcionario['telefone'];
    $email = $funcionario['email'];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Funcionário</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Editar Funcionário</h1>
        <form method="post">
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" class="form-control" id="nome" name="nome" value="<?php echo htmlspecialchars($nome); ?>" required>
            </div>
            <div class="form-group">
                <label for="cpf">CPF:</label>
                <input type="text" class="form-control" id="cpf" name="cpf" value="<?php echo htmlspecialchars($cpf); ?>" required>
            </div>
            <div class="form-group">
                <label for="telefone">Telefone:</label>
                <input type="tel" class="form-control" id="telefone" name="telefone" value="<?php echo htmlspecialchars($telefone); ?>">
            </div>
            <div class="form-group">
                <label for="email">E-mail:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Salvar</button>
            <a href="listar_funcionarios.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
