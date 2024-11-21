<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Carros</title>
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
        <h1 class="text-center mb-4">Lista de Carros</h1>

        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Id do veículo</th>
                    <th>Modelo</th>
                    <th>Marca</th>
                    <th>Ano</th>
                    <th>Placa</th>
                    <th>Cor</th>
                    <th>Km atual</th>
                    <th>Disponibilidade/Data do aluguel</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            <?php
                require_once "conexao.php";
                require_once "core.php";

                /**
                 * Função para pegar a última data de aluguel de um veículo.
                 *
                 * @param mysqli    $conexao    Conexão com o banco de dados.
                 * @param int       $id_veiculo ID do veículo.
                 * @return string               Disponibilidade ou data de aluguel do veículo.
                 */
                function ultimaDataAluguel($conexao, $id_veiculo) {
                    // Consulta para obter o último aluguel e verificar se ainda está em andamento
                    $sql = "SELECT a.data_inicio, a.data_fim
                            FROM alugueis a
                            JOIN alugueis_veiculos av ON a.id_aluguel = av.alugueis_id_aluguel
                            WHERE av.veiculos_id_veiculo = ? 
                            ORDER BY a.data_fim DESC 
                            LIMIT 1";
                            
                    $stmt = $conexao->prepare($sql);
                    $stmt->bind_param("i", $id_veiculo);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($result->num_rows > 0) {
                        $aluguel = $result->fetch_assoc();
                        $data_fim = $aluguel['data_fim'];
                        $data_atual = date('Y-m-d'); // Data atual no formato Y-m-d
                
                        // Verifica se o veículo está alugado no momento
                        if ($data_fim >= $data_atual) {
                            return "Indisponível (alugado até " . $data_fim . ")";
                        } else {
                            return "Disponível";
                        }
                    } else {
                        return "Disponível";  // Caso o veículo nunca tenha sido alugado
                    }
                }

                $resultados = listarCarros($conexao);

                foreach ($resultados as $modelo) {
                    $id_veiculo = $modelo[0];

                    // Pegar a última data de aluguel (se houver)
                    $ultimaData = ultimaDataAluguel($conexao, $id_veiculo);
                    $dataAluguel = $ultimaData ? $ultimaData : 'Disponivel';

                    echo "<tr>";
                    echo "<td>$id_veiculo</td>";
                    echo "<td>" . htmlspecialchars($modelo[1]) . "</td>";  // Modelo
                    echo "<td>" . htmlspecialchars($modelo[2]) . "</td>";  // Marca
                    echo "<td>" . htmlspecialchars($modelo[3]) . "</td>";  // Ano
                    echo "<td>" . htmlspecialchars($modelo[4]) . "</td>";  // Placa
                    echo "<td>" . htmlspecialchars($modelo[5]) . "</td>";  // Cor
                    echo "<td>" . htmlspecialchars($modelo[6]) . "</td>";  // Km atual
                    echo "<td>$dataAluguel</td>";  // Última data de aluguel
                    echo "<td>
                            <a href='editar.php?id=$id_veiculo' class='btn btn-warning btn-sm'>Editar</a>
                            <a href='excluir.php?id=$id_veiculo' class='btn btn-danger btn-sm'>Excluir</a>
                          </td>";
                    echo "</tr>";
                }
            ?>
            </tbody>
        </table>

        <div class="text-center mt-4">
            <a href="index.html" class="btn btn-primary"><< Página inicial</a><br><br>
        </div>
    </div>

    <footer class="footer text-center">
    <p>© 2024 Carromeu e Julieta - Todos os direitos reservados</p>
    </footer>

    <!-- Incluindo o Bootstrap JS e dependências via CDN -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>