<?php
require_once "conexao.php";

/**
 * Função para editar as informações de um cliente.
 *
 * Esta função recebe os dados do formulário para editar as informações de um cliente na base de dados.
 * Quando o formulário é enviado, as informações do cliente são atualizadas no banco de dados com base no ID do cliente.
 *
 * @param mysqli    $conexao                Conexão com o banco de dados.
 * @param int       $id                     ID do cliente a ser editado.
 * @param string    $nome                   Nome do cliente.
 * @param string    $cpf_cnpj               CPF ou CNPJ do cliente.
 * @param string    $endereco               Endereço do cliente.
 * @param string    $telefone               Telefone do cliente.
 * @param string    $email                  E-mail do cliente.
 * @param string    $carteira_motorista     Número da carteira de motorista do cliente.
 * @param string    $validade_carteira      Data de validade da carteira de motorista do cliente.
 * @return void
 */
function editarCliente($conexao, $id, $nome, $cpf_cnpj, $endereco, $telefone, $email, $carteira_motorista, $validade_carteira) {
    // Prepara a consulta SQL para atualizar os dados do cliente.
    $sql = "UPDATE clientes SET nome = ?, cpf_cnpj = ?, endereco = ?, telefone = ?, email = ?, carteira_motorista = ?, validade_carteira = ? WHERE id_cliente = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    
    // Verifica se a preparação da consulta foi bem-sucedida.
    if (!$stmt) {
        die('Erro na preparação da consulta: ' . mysqli_error($conexao));
    }
    
    // Liga os parâmetros e executa a consulta.
    mysqli_stmt_bind_param($stmt, "sssssssi", $nome, $cpf_cnpj, $endereco, $telefone, $email, $carteira_motorista, $validade_carteira, $id);
    mysqli_stmt_execute($stmt);
    
    // Fecha o statement.
    mysqli_stmt_close($stmt);
}

/**
 * Função para carregar as informações de um cliente baseado no ID.
 *
 * Esta função retorna as informações de um cliente específico a partir de seu ID. Ela é usada para preencher os campos do formulário de edição.
 *
 * @param mysqli $conexao   Conexão com o banco de dados.
 * @param int    $id        ID do cliente a ser carregado.
 * @return array|false      Retorna um array com os dados do cliente ou false em caso de erro.
 */
function carregarCliente($conexao, $id) {
    // Prepara a consulta SQL para obter os dados do cliente.
    $sql = "SELECT * FROM clientes WHERE id_cliente = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    
    // Verifica se a preparação da consulta foi bem-sucedida.
    if (!$stmt) {
        die('Erro na preparação da consulta: ' . mysqli_error($conexao));
    }
    
    // Liga o parâmetro e executa a consulta.
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    
    // Faz o fetch dos dados do cliente.
    $result = mysqli_stmt_get_result($stmt);
    
    // Verifica se foi encontrado algum cliente com o ID fornecido.
    if ($row = mysqli_fetch_assoc($result)) {
        mysqli_stmt_close($stmt);
        return $row; // Retorna os dados do cliente.
    } else {
        mysqli_stmt_close($stmt);
        return false; // Retorna false caso não encontre o cliente.
    }
}

// Verifica se o ID foi passado na URL para edição.
if (!isset($_GET['id'])) {
    header("Location: listar_clientes.php");
    exit();
}

$id = $_GET['id'];

// Processa o formulário de edição.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $cpf_cnpj = $_POST['cpf_cnpj'];
    $endereco = $_POST['endereco'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $carteira_motorista = $_POST['carteira_motorista'];
    $validade_carteira = $_POST['validade_carteira'];

    // Chama a função para editar o cliente.
    editarCliente($conexao, $id, $nome, $cpf_cnpj, $endereco, $telefone, $email, $carteira_motorista, $validade_carteira);

    // Redireciona de volta para a lista de clientes após a edição.
    header("Location: listar_clientes.php");
    exit();
} else {
    // Carrega os dados do cliente para preencher o formulário de edição.
    $cliente = carregarCliente($conexao, $id);
    
    // Se o cliente não for encontrado, redireciona para a lista de clientes.
    if (!$cliente) {
        header("Location: listar_clientes.php");
        exit();
    }

    // Extrai os dados do cliente para preencher os campos do formulário.
    $nome = $cliente['nome'];
    $cpf_cnpj = $cliente['cpf_cnpj'];
    $endereco = $cliente['endereco'];
    $telefone = $cliente['telefone'];
    $email = $cliente['email'];
    $carteira_motorista = $cliente['carteira_motorista'];
    $validade_carteira = $cliente['validade_carteira'];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Editar Cliente</h1>
        <form method="post">
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" class="form-control" value="<?php echo htmlspecialchars($nome); ?>" required>
            </div>
            <div class="form-group">
                <label for="cpf_cnpj">CPF/CNPJ:</label>
                <input type="text" id="cpf_cnpj" name="cpf_cnpj" class="form-control" value="<?php echo htmlspecialchars($cpf_cnpj); ?>" required>
            </div>
            <div class="form-group">
                <label for="endereco">Endereço:</label>
                <input type="text" id="endereco" name="endereco" class="form-control" value="<?php echo htmlspecialchars($endereco); ?>">
            </div>
            <div class="form-group">
                <label for="telefone">Telefone:</label>
                <input type="tel" id="telefone" name="telefone" class="form-control" value="<?php echo htmlspecialchars($telefone); ?>">
            </div>
            <div class="form-group">
                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>">
            </div>
            <div class="form-group">
                <label for="carteira_motorista">Carteira de Motorista:</label>
                <input type="text" id="carteira_motorista" name="carteira_motorista" class="form-control" value="<?php echo htmlspecialchars($carteira_motorista); ?>" required>
            </div>
            <div class="form-group">
                <label for="validade_carteira">Validade da Carteira:</label>
                <input type="date" id="validade_carteira" name="validade_carteira" class="form-control" value="<?php echo htmlspecialchars($validade_carteira); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Salvar</button>
            <a href="listar_clientes.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
