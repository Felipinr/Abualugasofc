<?php
require_once 'testalogin.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lançar Pagamento</title>
    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script src="js/jquery.mask.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="estilos/style.css" />
</head>

<body>
    <header
        class="container-fluid d-flex justify-content-between align-items-center">
        <div class="logo">
            <h2>Sistema de Aluguéis de Veículos</h2>
        </div>
        <div class="user-info text-end">
            <a href="http://lattes.cnpq.br/3766134688368012" target="_blank" class="text-decoration-none">Proprietário: Lucas Faria</a>
            <p>Data: 28/11/2024 - 13:55</p>
        </div>
    </header>

    <nav class="navbar navbar-dark navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown"
                aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a
                            class="nav-link active"
                            aria-current="page"
                            href="atividades.php">Todas as Ações</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="formEmprestimo.php">Alugar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pagamento_clienteSelect.php">Pagar</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a
                            class="nav-link dropdown-toggle"
                            href="#"
                            role="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Registros
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="listar_alugueis.php">Listar Aluguéis</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="listar_clientes.php">Listar Clientes</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="listar_funcionarios.php">Listar Funcionários</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="listar_pagamentos.php">Listar Pagamentos</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="listar_veiculos.php">Listar Veículos</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a
                            class="nav-link dropdown-toggle"
                            href="#"
                            role="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Cadastros
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="cadastro_empresa.php">Cadastrar uma Empresa</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="cadastro_funcionario.php">Cadastrar um Funcionário</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="cadastro_pessoa.php">Cadastrar uma Pessoa</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="cadastro_veiculo.php">Cadastrar um Veículo</a>
                            </li>
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
        <h2 class="text-center mb-4">Lançar Pagamento</h2>
        <form id="formPagamento" action="pagamento_terminar.php" method="POST">
            <input type="hidden" name="id_aluguel" value="<?php echo $_GET['id_aluguel']; ?>">

            <div class="mb-3">
                <label for="data_pagamento" class="form-label">Data Atual:</label>
                <input type="date" name="data_pagamento" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="metodo" class="form-label">Método de Pagamento:</label>
                <select name="metodo" class="form-select" required>
                    <option value="Dinheiro">Dinheiro</option>
                    <option value="Cartão">Cartão</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="preco_por_km" class="form-label">Preço por KM:</label>
                <input type="number" name="preco_por_km" step="0.01" min="0" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="kmtotaldoaluguel" class="form-label">Total de KM do Aluguel:</label>
                <span id="kmtotaldoaluguel">0.00</span> KM
            </div>

            <div class="mb-3">
                <label for="valor" class="form-label">Valor Final:</label>
                <input type="number" name="valor" step="0.01" min="0" class="form-control" readonly>
            </div>

            <h4>Veículos</h4>
            <hr>
            <?php
            require_once "conexao.php";
            require_once "operacoes.php";

            $carros = listarVeiculosEmprestimo($conexao, $_GET['id_aluguel']);

            foreach ($carros as $carroEmprestimo) {
                $veiculo = listarVeiculoPorId($conexao, $carroEmprestimo[0]);

                if (count($veiculo) === 12) {
                    echo "<input type='hidden' name='id_veiculo[]' value='{$veiculo[0]}'>";
                    echo "<div class='mb-3'>";
                    echo "<p><strong>Veículo:</strong> {$veiculo[1]} - {$veiculo[2]}</p>";
                    echo "<p><strong>Km Atual:</strong> <span class='km-atual'>{$veiculo[11]}</span></p>";
                    echo "<label for='kmpercorrido' class='form-label'>Km Percorrido:</label>";
                    echo "<input type='number' name='kmpercorrido[]' class='form-control kmpercorrido' step='0.01' min='0' required>";
                    echo "<p><strong>Nova Quilometragem:</strong> <span class='nova-km'>0.00</span></p>";
                    echo "</div>";
                    echo "<hr>";
                } else {
                    echo "<p>Erro ao carregar informações do veículo.</p>";
                }
            }
            ?>

            <div class="text-center">
                <input type="submit" value="Lançar Pagamento" class="btn btn-primary">
            </div>
        </form>
        <div class="text-center">
            <a href="pagamento_emprestimoSelect.php" class="btn btn-primary mt-3">Voltar</a>
            <a href="atividades.php" class="btn btn-primary mt-3">Voltar a Página de Atividades</a>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Instituto Federal Goiano. Todos os direitos reservados.</p>
    </footer>

    <script>
        $(document).ready(function() {
            $(".kmpercorrido").on("input", function() {
                const kmAtual = parseFloat($(this).closest(".mb-3").find(".km-atual").text());
                const kmPercorrido = parseFloat($(this).val()) || 0;
                const novaKm = kmAtual + kmPercorrido;
                $(this).closest(".mb-3").find(".nova-km").text(novaKm.toFixed(2));
            });

            function calcularValor() {
                let totalKmPercorrido = 0;

                $(".kmpercorrido").each(function() {
                    totalKmPercorrido += parseFloat($(this).val()) || 0;
                });

                const precoPorKm = parseFloat($("input[name='preco_por_km']").val()) || 0;
                const valorFinal = totalKmPercorrido * precoPorKm;

                $("input[name='valor']").val(valorFinal.toFixed(2));
                $("#kmtotaldoaluguel").text(totalKmPercorrido.toFixed(2));
            }

            $(".kmpercorrido, input[name='preco_por_km']").on("input", calcularValor);

            calcularValor();

            $("#formPagamento").validate({
                rules: {
                    preco_por_km: {
                        required: true,
                        number: true,
                        min: 0,
                    },
                    data_pagamento: {
                        required: true,
                    },
                },
                messages: {
                    preco_por_km: {
                        required: "O preço do km rodado do é obrigatório.",
                        number: "O preço do km deve ser um número válido.",
                        min: "O preço não pode ser um valor negativo",
                    },
                    data_pagamento: {
                        required: "Informe a data em que o pagamento foi feito.",
                    },
                },
            });
        });
    </script>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>