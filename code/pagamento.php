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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script> 
    <link rel="stylesheet" href="estilos/style.css" />

    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
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

        .slogan {
            text-align: center;
            margin: 20px 0;
        }

    </style>

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
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.html">Carromeu e Julieta</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="text-center mb-4">Selecione um Cliente</h1>

        <div class="card shadow-lg p-4 mb-4">
            <form id="form" method="POST" action="pagamento2.php">
                <div class="form-group">
                    <label for="cliente">Escolha um Cliente:</label>
                    <br>
                    <select name="cliente" id="cliente" class="form-control" required>
                        <option value="">Selecione um cliente</option>
                        <?php
                        $query_clientes = "SELECT id_cliente, nome FROM clientes";
                        $result_clientes = mysqli_query($conexao, $query_clientes);

                        // Exibe os clientes no select
                        while ($row = mysqli_fetch_assoc($result_clientes)) {
                            echo "<option value='{$row['id_cliente']}'>{$row['nome']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Mostrar Veículos Alugados</button>
            </form>
        </div>
        <div class="text-center">
            <input type="submit" value="Selecionar Cliente" class="btn btn-primary">
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
        <script>
            $(document).ready(function () {
                $("#form").validate({
                    rules: {
                        cliente: {
                            required: true
                        }
                    },
                    messages: {
                        cliente: {
                            required: "Selecione um cliente para ver os veículos alugados"
                        }
                    }
                });
            });
        </script>
    </div>

    <footer class="footer text-center mt-auto py-3 bg-dark text-white">
        <div class="container">
            <p>© 2024 Carromeu e Julieta - Todos os direitos reservados</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-cB5t5ZzVHOzI1Q5JjZz0DwG8qB5JjZJ5cR5LPt5c5C5J5J5J5J5J5J5J5J5J5J5J" crossorigin="anonymous"></script>
</body>

</html>
