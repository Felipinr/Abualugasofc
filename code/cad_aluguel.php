<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleção de Funcionário e Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="jquery-3.7.1.min.js"></script>
    <script src="jquery.validate.min.js"></script>
    <style>
        body {
            background-color: #f5f7fa;
        }
        .form-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
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
        .btn-back {
            background-color: #6c757d;
            color: white;
            border: none;
        }
        .btn-back:hover {
            background-color: #5a6268;
        }

        body {
            background-color: #f5f7fa;
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
            text-align: center;
        }

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
    </style>
</head>
<body>

    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="form-container">
            <h2 class="form-heading text-center">Selecione Funcionário e Cliente</h2>
            <form id="form" action="pagina2.php" method="POST">
                <div class="mb-3">
                    <label for="funcionario" class="form-label">Funcionário:</label>
                    <select id="funcionario" name="id_funcionario" class="form-select" required>
                    <option value=""></option>

                        <?php
                        require_once 'conexao.php';
                        $query_funcionarios = "SELECT id_funcionario, nome FROM funcionarios";
                        $result_funcionarios = mysqli_query($conexao, $query_funcionarios);
                        while ($row = mysqli_fetch_assoc($result_funcionarios)) {
                            echo "<option value='{$row['id_funcionario']}'>{$row['nome']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="cliente" class="form-label">Cliente:</label>
                    <select id="cliente" name="id_cliente" class="form-select" required>
                        <option value=""></option>
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
                    <button type="submit" class="btn btn-custom">Próxima Etapa</button>
                    <a href="index.html" class="btn btn-back ms-2">Voltar ao Início</a>
                </div>
            </form>
        </div>
    </div>
 <footer class="footer text-center">
 <p>© 2024 Carromeu e Julieta - Todos os direitos reservados</p>
    </footer>
    <script>
    $(document).ready(function() {
        // Aplica a validação do formulário
        $("#form").validate({
            rules: {
                "id_funcionario": {
                    required: true
                },
                "id_cliente": {
                    required: true
                }
            },
            messages: {
                "id_funcionario": {
                    required: "Por favor, selecione um funcionário."
                },
                "id_cliente": {
                    required: "Por favor, selecione um cliente."
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    });
</script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
