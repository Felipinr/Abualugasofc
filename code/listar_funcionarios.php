<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Funcionários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f5f7fa;
        }

        .navbar {
            background-color: #343a40;
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }

        .navbar-nav .nav-link {
            color: #ffffff !important;
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
        }

        .table-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.html">Carromeu e Julieta</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5 table-container">
        <h1 class="text-center mb-4">Lista de Funcionários</h1>

        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID do Funcionário</th>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Telefone</th>
                    <th>E-mail</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                /**
                 * Exibe uma lista de funcionários com suas informações detalhadas, utilizando a estrutura HTML com o Bootstrap.
                 * 
                 * A página inclui uma barra de navegação, uma tabela com os dados dos funcionários (ID, Nome, CPF, Telefone, E-mail)
                 * e botões de ação para editar ou excluir funcionários. Além disso, a página é responsiva e contém um rodapé com informações de copyright.
                 *
                 * @param void
                 * @return void
                 */
            
                require_once "conexao.php";
                require_once "core.php";
                require_once 'login2.php';

                $resultados = listarFuncionarios($conexao);

                foreach ($resultados as $funcionario) {
                    $id_funcionario = $funcionario[0];
                    echo "<tr>";
                    echo "<td>$id_funcionario</td>";
                    echo "<td>" . htmlspecialchars($funcionario[1]) . "</td>";
                    echo "<td>" . htmlspecialchars($funcionario[2]) . "</td>";
                    echo "<td>" . htmlspecialchars($funcionario[3]) . "</td>";
                    echo "<td>" . htmlspecialchars($funcionario[4]) . "</td>";
                    echo "<td>
                            <a href='editar_funcionario.php?id=$id_funcionario' class='btn btn-warning btn-sm'>Editar</a>
                            <a href='excluir_funcionario.php?id=$id_funcionario' class='btn btn-danger btn-sm'>Excluir</a>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
        <div class="text-center mt-4">
            <a href="index.html" class="btn btn-primary">
                << Página inicial</a><br><br>
        </div>
    </div>
    <div class="text-center mt-4">
        <a href="gerar_pdffunc.php" class="btn btn-success">Gerar PDF</a>
    </div>

    <footer class="footer text-center">
        <p>© 2024 Carromeu e Julieta - Todos os direitos reservados</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>