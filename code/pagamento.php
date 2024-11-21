<?php
require_once 'conexao.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selecionar Cliente</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9aIt2xL4pFha7z0m8XJwFzqH4Yd8pK2Vm2bKsaF4/a5D3O2XIIU7+a5U8Ggdksv" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script> 
    <link rel="stylesheet" href="estilos/style.css" />
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Selecione um Cliente</h1>

        <div class="card shadow-lg p-4 mb-4">
            <form id="form" method="POST" action="pagamento2.php">
                <div class="form-group">
                    <label for="cliente">Escolha um Cliente:</label>
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

        <div id="cliente-error" class="alert alert-danger" style="display:none;">
            <strong>Erro!</strong> Selecione um cliente para continuar.
        </div>

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

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0tW+DezIH5DsJth7a+G/yC1V6lXaJ6uT46To6C9mFw0P6oXz" crossorigin="anonymous"></script>
</body>

</html>
