<?php
require_once 'conexao.php';
require_once 'core.php';

/**
 * Exibe os empréstimos de um cliente para seleção de pagamento.
 *
 * Este script é responsável por exibir os empréstimos de um cliente, permitindo
 * que o usuário selecione o empréstimo para registrar o pagamento. O script
 * valida se o ID do cliente foi enviado e recupera os empréstimos associados a
 * esse cliente no banco de dados.
 * 
 * O script segue os seguintes passos:
 * 1. Validação do ID do cliente enviado via POST.
 * 2. Recuperação dos empréstimos relacionados ao cliente a partir do banco de dados.
 * 3. Exibição dos empréstimos com os dados dos veículos (modelo e placa).
 * 4. Exibição de um formulário para a seleção de pagamento.
 * 5. Direcionamento para a próxima página de pagamento.
 * 6. Exibição de alertas caso o cliente não tenha empréstimos.
 *
 * @param mysqli $conexao Conexão com o banco de dados.
 * @return void
 */

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
    
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
        }

        .navbar {
            background-color: #343a40;
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }

        .navbar-nav .nav-link:hover {
            background-color: #495057;
            border-radius: 5px;
        }

        .footer {
            margin-top: auto;
            padding: 20px 0;
            background-color: #343a40;
            color: white;
            width: 100%;
        }
    </style>

</head>

<body>

 <!-- Navbar -->
 <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.html">Carromeu e Julieta</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>


<div class="container mt-5">
    <h3 class="text-center mb-4">Selecionar Empréstimo</h3>

    <form action="pagamento2.php" method="GET">
        <?php
        if ($quantidade > 0) {
            echo "<div class='mb-3'>";
            echo "<label class='form-label'>Empréstimos:</label>";
            echo "<div class='list-group'>"; 

            
            foreach ($emprestimos as $emprestimo) {
                $modelo = $emprestimo['modelo'];
                $placa = $emprestimo['placa'];

                
                echo "<div class='list-group-item'>";
                echo "<strong>Modelo:</strong> $modelo <br>";
                echo "<strong>Placa:</strong> $placa <br>";
                echo "</div>";
            }

            echo "</div>";
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
        <a href="pagamento3.php?id_cliente=<?php echo $id_cliente; ?>" class="btn btn-primary ">Próxima página</a>
    </div>
    <div class="text-center mt-4">
        <a href="pagamento.php" class="btn btn-danger">Voltar</a>
        <a href="index.html" class="btn btn-secondary">Voltar ao início</a>
    </div>
</div>

<!-- Footer -->
<footer class="footer text-center">
        <p>© 2024 Carromeu e Julieta - Todos os direitos reservados</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
