<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Clientes</title>
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
            <a class="navbar-brand" href="index.html">Carromeu e julieta</a>
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
        <h1 class="text-center mb-4">Lista de Clientes</h1>

        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>CPF/CNPJ</th>
                    <th>Endereço</th>
                    <th>Telefone</th>
                    <th>E-mail</th>
                    <th>Carteira de Motorista</th>
                    <th>Validade da Carteira</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                /**
                 * Exibe uma lista de clientes cadastrados, mostrando informações como ID, Nome, CPF/CNPJ, Endereço, Telefone,
                 * E-mail, Carteira de Motorista e Validade da Carteira, com opções para editar ou excluir cada cliente.
                 * 
                 * A página utiliza o framework Bootstrap para responsividade e estilo. A tabela é preenchida com dados dinâmicos
                 * extraídos de um banco de dados via PHP.
                 * 
                 * @param  
                 * @return void
                 */

                require_once "conexao.php";
                require_once "core.php";

                $resultados = listarClientes($conexao);

                foreach ($resultados as $cliente) {
                    $id_cliente = $cliente[0];
                    echo "<tr>";
                    echo "<td>$id_cliente</td>";
                    echo "<td>" . htmlspecialchars($cliente[1]) . "</td>";
                    echo "<td>" . htmlspecialchars($cliente[2]) . "</td>";
                    echo "<td>" . htmlspecialchars($cliente[3]) . "</td>";
                    echo "<td>" . htmlspecialchars($cliente[4]) . "</td>";
                    echo "<td>" . htmlspecialchars($cliente[5]) . "</td>";
                    echo "<td>" . htmlspecialchars($cliente[6]) . "</td>";
                    echo "<td>" . htmlspecialchars($cliente[7]) . "</td>";
                    echo "<td>
                            <a href='editar_cliente.php?id=$id_cliente' class='btn btn-warning btn-sm'>Editar</a>
                            <a href='excluir_cliente.php?id=$id_cliente' class='btn btn-danger btn-sm'>Excluir</a>
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
        <a href="gerar_pdfcli.php" class="btn btn-success">Gerar PDF</a>
    </div>


    <footer class="footer text-center">
        <p>© 2024 Carromeu e Julieta - Todos os direitos reservados</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>