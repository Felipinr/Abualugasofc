<?php

/**
 * Exibe uma interface para inserir detalhes do aluguel de veículos.
 *
 * @param mysqli $conexao Conexão ativa com o banco de dados.
 * @return void
 */

?>



<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Aluguel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="jquery-3.7.1.min.js"></script> <!-- Mantém a versão completa do jQuery -->
    <script src="jquery.validate.min.js"></script> <!-- jQuery Validation -->
    <style>
        body {
            background-color: #f5f7fa;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
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

        /* Estilo para mensagens de erro */
        #data_inicio-error,
        #data_fim-error,
        #valor_km-error {
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
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.html">Carromeu e Julieta</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="form-container">
            <h2 class="form-heading text-center">Detalhes do Aluguel</h2>
            <form id="form" action="processamentoaluguel.php" method="POST">
    <input type="hidden" name="id_funcionario" value="<?php echo $_POST['id_funcionario']; ?>">
    <input type="hidden" name="id_cliente" value="<?php echo $_POST['id_cliente']; ?>">
    <input type="hidden" name="veiculos" value="<?php echo implode(',', $_POST['veiculos']); ?>">

    <div class="mb-3">
        <label for="data_inicio" class="form-label">Data de Início:</label>
        <input type="date" id="data_inicio" name="data_inicio" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="data_fim" class="form-label">Data Prevista de Entrega:</label>
        <input type="date" id="data_fim" name="data_fim" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="valor_km" class="form-label">Valor do KM Rodado:</label>
        <input type="number" id="valor_km" name="valor_km" class="form-control" step="0.01" required>
    </div>

    <button type="submit" class="btn btn-custom">Confirmar Aluguel</button>
</form>
        </div>
    </div>

    <footer class="footer text-center">
        <p>© 2024 Carromeu e Julieta - Todos os direitos reservados</p>
    </footer>

    <script>
        $(document).ready(function() {
            // Método personalizado para verificar se a data final é após a data inicial
            $.validator.addMethod("afterStartDate", function(value, element) {
                var startDate = $("#data_inicio").val();
                return value && startDate && new Date(value) > new Date(startDate);
            }, "A data de entrega deve ser após a data de início.");

            // Validação do formulário
            $("#form").validate({
                rules: {
                    data_inicio: {
                        required: true,
                        date: true
                    },
                    data_fim: {
                        required: true,
                        date: true,
                        afterStartDate: true
                    },
                    valor_km: {
                        required: true,
                        number: true,
                        min: 0.01
                    }
                },
                messages: {
                    data_inicio: {
                        required: "Por favor, informe a data de início.",
                        date: "Por favor, informe uma data válida."
                    },
                    data_fim: {
                        required: "Por favor, informe a data de entrega.",
                        date: "Por favor, informe uma data válida.",
                        afterStartDate: "A data de entrega deve ser após a data de início."
                    },
                    valor_km: {
                        required: "Por favor, informe o valor do KM rodado.",
                        number: "Por favor, insira um valor válido.",
                        min: "O valor do KM deve ser maior que 0."
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