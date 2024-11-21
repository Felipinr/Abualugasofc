<?php
require_once 'conexao.php';
require_once 'core.php'
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selecionar Empréstimos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="estilos/style.css" />
</head>

<body>
    <header class="container-fluid d-flex justify-content-between align-items-center">
        <div class="logo">
            <h2>Sistema de Aluguéis de Veículos</h2>
        </div>
        <div class="user-info text-end">
            <p>Data: 28/11/2024 - 13:55</p>
        </div>
    </header>

    <nav class="navbar navbar-dark navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="atividades.php">Todas as Ações</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="formEmprestimo.php">Alugar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pagamento_clienteSelect.php">Pagar</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Registros
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="listar_alugueis.php">Listar Aluguéis</a></li>
                            <li><a class="dropdown-item" href="listar_clientes.php">Listar Clientes</a></li>
                            <li><a class="dropdown-item" href="listar_funcionarios.php">Listar Funcionários</a></li>
                            <li><a class="dropdown-item" href="listar_pagamentos.php">Listar Pagamentos</a></li>
                            <li><a class="dropdown-item" href="listar_veiculos.php">Listar Veículos</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Cadastros
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="cadastro_empresa.php">Cadastrar uma Empresa</a></li>
                            <li><a class="dropdown-item" href="cadastro_funcionario.php">Cadastrar um Funcionário</a></li>
                            <li><a class="dropdown-item" href="cadastro_pessoa.php">Cadastrar uma Pessoa</a></li>
                            <li><a class="dropdown-item" href="cadastro_veiculo.php">Cadastrar um Veículo</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a id="deslogar" class="nav-link" href="deslogar.php">Fazer Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <h3 class="text-center mb-4">Selecionar Empréstimo</h3>

        <form action="pagamento_preencher.php" method="GET">
            <?php
            require_once "conexao.php";
            require_once "core.php";
            $id_cliente = $_GET['id_cliente'];

            $emprestimos = listarEmprestimoCliente($conexao, $id_cliente);
            $quantidade = count($emprestimos); // Usando count para evitar problemas com tamanho do array

            if ($quantidade > 0) {
                echo "<div class='mb-3'>";
                echo "<label for='id_aluguel' class='form-label'>Empréstimos:</label>";
                echo "<select name='id_aluguel' id='id_aluguel' class='form-select' required>";

                foreach ($emprestimos as $emprestimo) {
                    $id_aluguel = $emprestimo['id_aluguel']; // Acessando os dados pela chave associativa
                    $datainicial_aluguel = $emprestimo['datainicial_aluguel'];
                    $datafinal_aluguel = $emprestimo['datafinal_aluguel'];

                    echo "<option value='$id_aluguel'>$datainicial_aluguel > $datafinal_aluguel</option>";
                }
                echo "</select>";
                echo "</div>";
                echo "<input type='hidden' name='id_cliente' value='$id_cliente'>";
                echo "<div class='text-center'>";
                echo "<input type='submit' value='Preencher dados do pagamento' class='btn btn-primary'>";
                echo "</div>";
            } else {
                echo "<div class='alert alert-warning' role='alert'>Não há empréstimos para esse cliente.</div>";
            }
            ?>
        </form>
        <div class="text-center">
            <a href="pagamento_clienteSelect.php" class="btn btn-primary mt-3">Voltar</a>
            <a href="atividades.php" class="btn btn-primary mt-3">Voltar a Página de Atividades</a>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Instituto Federal Goiano. Todos os direitos reservados.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
