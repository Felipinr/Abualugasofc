<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleção de Veículos</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="jquery-3.7.1.min.js"></script> <!-- Mantém a versão completa do jQuery -->
    <script src="jquery.validate.min.js"></script> <!-- jQuery Validation -->
    <style>
        body {
            background-color: #f5f7fa;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        #veiculos\[\]-error {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: rgba(255, 0, 0, 0.8);
            /* Cor de fundo vermelha com opacidade */
            color: white;
            font-size: 16px;
            padding: 10px;
            text-align: center;
            font-weight: bold;
            z-index: 1000;
            /* Garante que a mensagem fique acima dos outros elementos */
        }

        .form-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-heading {
            margin-bottom: 20px;
        }

        .btn-custom {
            background-color: #4a90e2;
            color: white;
            border: none;
        }

        .btn-custom:hover {
            background-color: #357abd;
        }

        .back-link {
            display: block;
            margin-top: 20px;
            color: #4a90e2;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .btn-group-custom {
            margin-top: 20px;
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

        .card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin: 20px 0;
        }

        .card {
            width: 18rem;
        }

        .card-img-top {
            height: 200px;
            object-fit: cover;
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
    <div class="container form-container mt-4">
        <h2>Selecione os Veículos</h2>
        <form id="form" action="pagina3.php" method="POST">
            <input type="hidden" name="id_funcionario" value="<?php echo $_POST['id_funcionario']; ?>">
            <input type="hidden" name="id_cliente" value="<?php echo $_POST['id_cliente']; ?>">
            <?php
            /**
             * Exibe uma lista de veículos disponíveis para seleção.
             *
             * Realiza uma consulta no banco de dados para obter os veículos disponíveis e gera
             * os checkboxes correspondentes para seleção pelo usuário.
             *
             * @param mysqli $conexao Conexão ativa com o banco de dados.
             * @return void
             */
            require_once 'conexao.php';
            $query_veiculos = "SELECT id_veiculo, placa FROM veiculos WHERE disponivel = 1";
            $result_veiculos = mysqli_query($conexao, $query_veiculos);

            while ($row = mysqli_fetch_assoc($result_veiculos)) {
                echo "<label><input type='checkbox' name='veiculos[]' value='{$row['id_veiculo']}'> {$row['placa']}</label><br>";
            }
            ?>
            <button type="submit" class="btn btn-custom mt-3">Próxima Etapa</button>
        </form>
    </div>
    <script>
        $(document).ready(function() {
            $("#form").validate({
                rules: {
                    'veiculos[]': {
                        required: true
                    }
                },
                messages: {
                    'veiculos[]': {
                        required: "Por favor, selecione pelo menos um veículo."
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        });
    </script>
    <footer class="footer text-center">
        <p>© 2024 Carromeu e Julieta - Todos os direitos reservados</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>