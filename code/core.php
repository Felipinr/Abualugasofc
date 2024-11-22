<?php

/**
 * Lista todos os veículos cadastrados no banco de dados.
 *
 * @param mysqli $conexao   Conexão ativa com o banco de dados.
 * @return array            Lista de veículos.
 */
function listarCarros($conexao)
{
    $sql = "SELECT * FROM veiculos";

    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id_veiculo, $modelo, $marca, $ano, $placa, $cor, $km_atual, $airbag, $num_bancos, $num_portas, $combustivel, $cambio, $ar_coondicionado, $direcao, $som, $bluetooth, $gps, $sensor_estacionamento, $camera_re, $disponivel);
    mysqli_stmt_store_result($stmt);

    $lista = [];
    if (mysqli_stmt_num_rows($stmt) > 0) {
        while (mysqli_stmt_fetch($stmt)) {
            $lista[] = [$id_veiculo, $modelo, $marca, $ano, $placa, $cor, $km_atual, $airbag, $num_bancos, $num_portas, $combustivel, $cambio, $ar_coondicionado, $direcao, $som, $bluetooth, $gps, $sensor_estacionamento, $camera_re, $disponivel];
        }
    }

    mysqli_stmt_close($stmt);
    return $lista;
}

/**
 * Lista todos os clientes cadastrados no banco de dados.
 *
 * @param mysqli $conexao   Conexão ativa com o banco de dados.
 * @return array            Lista de clientes.
 */
function listarClientes($conexao)
{
    $sql = "SELECT * FROM clientes";

    $stmt = mysqli_prepare($conexao, $sql);
    if (!$stmt) {
        die('Erro na preparação da consulta: ' . mysqli_error($conexao));
    }

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id_cliente, $nome, $cpf_cnpj, $endereco, $telefone, $email, $carteira_motorista, $validade_carteira, $fisico_juridico);
    mysqli_stmt_store_result($stmt);

    $lista = [];
    if (mysqli_stmt_num_rows($stmt) > 0) {
        while (mysqli_stmt_fetch($stmt)) {
            $lista[] = [$id_cliente, $nome, $cpf_cnpj, $endereco, $telefone, $email, $carteira_motorista, $validade_carteira];
        }
    }

    mysqli_stmt_close($stmt);
    return $lista;
}

/**
 * Lista todos os funcionários cadastrados no banco de dados.
 *
 * @param mysqli $conexao Conexção ativa com o banco de dados.
 * @return array           Lista de funcionários.
 */
function listarFuncionarios($conexao)
{
    $sql = "SELECT * FROM funcionarios";

    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id_funcionario, $nome, $cpf, $telefone, $email, $senha);
    mysqli_stmt_store_result($stmt);

    $lista = [];
    if (mysqli_stmt_num_rows($stmt) > 0) {
        while (mysqli_stmt_fetch($stmt)) {
            $lista[] = [$id_funcionario, $nome, $cpf, $telefone, $email, $senha];
        }
    }

    mysqli_stmt_close($stmt);
    return $lista;
}

/**
 * Busca o modelo do veículo pelo ID.
 *
 * @param mysqli $conexao Conexão ativa com o banco de dados.
 * @param int $id_veiculo ID do veículo.
 * @return string         Modelo do veículo ou mensagem de erro.
 */
function buscarNomeSituacaoPorId($conexao, $id_veiculo)
{
    if (!$conexao) {
        die("Falha na conexão com o banco de dados: " . mysqli_connect_error());
    }

    $sql = "SELECT modelo FROM veiculos WHERE id_veiculo = ?";
    if ($stmt = mysqli_prepare($conexao, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id_veiculo);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $modelo);

        if (mysqli_stmt_fetch($stmt)) {
            mysqli_stmt_close($stmt);
            return $modelo;
        } else {
            mysqli_stmt_close($stmt);
            return "Veículo não encontrado.";
        }
    } else {
        return "Erro ao preparar a consulta SQL: " . mysqli_error($conexao);
    }
}

/**
 * Salva um novo cliente no banco de dados.
 *
 * @param mysqli $conexao   Conexão ativa com o banco de dados.
 * @param string $nome      Nome do cliente.
 * @return int              ID do cliente inserido.
 */
function salvarCliente($conexao, $nome)
{
    $sql = "INSERT INTO cliente (nome) VALUES (?)";
    $stmt = mysqli_prepare($conexao, $sql);

    mysqli_stmt_bind_param($stmt, "s", $nome);
    mysqli_stmt_execute($stmt);

    $id = mysqli_stmt_insert_id($stmt);
    mysqli_stmt_close($stmt);

    return $id;
}

/**
 * Salva um novo funcionário no banco de dados.
 *
 * @param mysqli $conexao   Conexão ativa com o banco de dados.
 * @param string $nome      Nome do funcionário.
 * @return int              ID do funcionário inserido.
 */
function salvarFuncionario($conexao, $nome)
{
    $sql = "INSERT INTO funcionario (nome) VALUES (?)";
    $stmt = mysqli_prepare($conexao, $sql);

    mysqli_stmt_bind_param($stmt, "s", $nome);
    mysqli_stmt_execute($stmt);

    $id = mysqli_stmt_insert_id($stmt);
    mysqli_stmt_close($stmt);

    return $id;
}

/**
 * Salva um novo veículo no banco de dados.
 *
 * @param mysqli $conexao   Conexão ativa com o banco de dados.
 * @param string $km        Kilometragem atual do veículo.
 * @param string $marca     Marca do veículo.
 * @param string $modelo    Modelo do veículo.
 * @return int              ID do veículo inserido.
 */
function salvarVeiculo($conexao, $km, $marca, $modelo)
{
    $sql = "INSERT INTO veiculo (km_atual, marca, modelo) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conexao, $sql);

    mysqli_stmt_bind_param($stmt, "sss", $km, $marca, $modelo);
    mysqli_stmt_execute($stmt);

    $id = mysqli_stmt_insert_id($stmt);
    mysqli_stmt_close($stmt);

    return $id;
}

/**
 * Salva um novo empréstimo no banco de dados.
 *
 * @param mysqli $conexao       Conexão ativa com o banco de dados.
 * @param int $idfuncionario    ID do funcionário.
 * @param int $idcliente        ID do cliente.
 * @return int                  ID do empréstimo inserido.
 */
function salvarEmprestimo($conexao, $idfuncionario, $idcliente)
{
    $sql = "INSERT INTO emprestimo (idfuncionario, idcliente) VALUES (?, ?)";
    $stmt = mysqli_prepare($conexao, $sql);

    mysqli_stmt_bind_param($stmt, "ii", $idfuncionario, $idcliente);
    mysqli_stmt_execute($stmt);

    $id = mysqli_stmt_insert_id($stmt);
    mysqli_stmt_close($stmt);

    return $id;
}

/**
 * Salva a relação de um veículo com um empréstimo no banco de dados.
 *
 * @param mysqli $conexao       Conexão ativa com o banco de dados.
 * @param int $idemprestimo     ID do empréstimo.
 * @param int $idveiculo        ID do veículo.
 * @return int                  ID da relação inserida.
 */
function salvarVeiculoEmprestimo($conexao, $idemprestimo, $idveiculo)
{
    $km_inicial = kmInicialVeiculo($conexao, $idveiculo);
    $km_final = 0;

    $sql = "INSERT INTO alugueis_veiculos (idemprestimo, idveiculo, km_inicial, km_final) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexao, $sql);

    mysqli_stmt_bind_param($stmt, "iiss", $idemprestimo, $idveiculo, $km_inicial, $km_final);
    mysqli_stmt_execute($stmt);

    $id = mysqli_stmt_insert_id($stmt);
    mysqli_stmt_close($stmt);

    return $id;
}

/**
 * Obtém a quilometragem inicial de um veículo.
 *
 * @param mysqli $conexao   Conexão ativa com o banco de dados.
 * @param int $idveiculo    ID do veículo.
 * @return string           Quilometragem inicial do veículo.
 */
function kmInicialVeiculo($conexao, $idveiculo)
{
    $sql = "SELECT km_atual FROM veiculo WHERE idveiculo = ?";
    $stmt = mysqli_prepare($conexao, $sql);

    mysqli_stmt_bind_param($stmt, 'i', $idveiculo);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result($stmt, $km);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    return $km;
}

/**
 * Efetua um pagamento relacionado a um empréstimo.
 *
 * @param mysqli $conexao   Conexão ativa com o banco de dados.
 * @param int $idemprestimo ID do empréstimo.
 * @param float $valor      Valor do pagamento.
 * @param float $precokm    Preço por quilômetro.
 * @param string $metodo    Método de pagamento.
 * @return int              ID do pagamento inserido.
 */
function efetuarPagamento($conexao, $idemprestimo, $valor, $precokm, $metodo)
{
    $sql = "INSERT INTO pagamento (idemprestimo, valor, preco_por_km, metodo) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexao, $sql);

    mysqli_stmt_bind_param($stmt, "idds", $idemprestimo, $valor, $precokm, $metodo);
    mysqli_stmt_execute($stmt);

    $id = mysqli_stmt_insert_id($stmt);
    mysqli_stmt_close($stmt);

    return $id;
}

/**
 * Atualiza a quilometragem final de um empréstimo para um veículo.
 *
 * @param mysqli $conexao   Conexão ativa com o banco de dados.
 * @param float $km_final   Quilometragem final do veículo.
 * @param int $idemprestimo ID do empréstimo.
 * @param int $idveiculo    ID do veículo.
 */
function atualiza_km_final($conexao, $km_final, $idemprestimo, $idveiculo)
{
    $sql = "UPDATE emprestimo_has_veiculo SET km_final = ? WHERE idemprestimo = ? AND idveiculo = ?";
    $stmt = mysqli_prepare($conexao, $sql);

    mysqli_stmt_bind_param($stmt, "dii", $km_final, $idemprestimo, $idveiculo);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    atualiza_km_atual($conexao, $km_final, $idveiculo);
}

/**
 * Atualiza a quilometragem atual de um veículo.
 *
 * @param mysqli $conexao   conexão ativa com o banco de dados.
 * @param int $km_atual     Quilometragem atual do veículo.
 * @param int $id_veiculo    ID do veículo.
 */
function atualiza_km_atual($conexao, $km_atual, $id_veiculo)
{
    $sql = "UPDATE veiculo SET km_atual = ? WHERE id_veiculo = ?";
    $stmt = mysqli_prepare($conexao, $sql);

    mysqli_stmt_bind_param($stmt, "ii", $km_atual, $id_veiculo);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}


function listarEmprestimoCliente($conexao, $id_cliente)
{

    $sql = "
    SELECT 
        a.id_aluguel, 
        a.id_funcionario, 
        a.id_cliente, 
        a.data_inicio, 
        a.data_fim, 
        v.id_veiculo, 
        v.modelo, 
        v.placa, 
        v.km_atual, 
        c.nome
    FROM 
        alugueis a
    INNER JOIN 
        clientes c ON a.id_cliente = c.id_cliente
    INNER JOIN 
        alugueis_veiculos av ON a.id_aluguel = av.alugueis_id_aluguel
    INNER JOIN 
        veiculos v ON av.veiculos_id_veiculo = v.id_veiculo
    WHERE 
        a.id_cliente = ?";  

    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_cliente);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    mysqli_stmt_bind_result($stmt, $id_aluguel, $id_funcionario, $id_cliente, $data_inicio, $data_fim, $id_veiculo, $modelo, $placa, $km_atual, $nome);

    $lista = [];
    if (mysqli_stmt_num_rows($stmt) > 0) {
        while (mysqli_stmt_fetch($stmt)) {

            $lista[] = [
                'id_aluguel' => $id_aluguel,
                'id_funcionario' => $id_funcionario,
                'id_cliente' => $id_cliente,
                'data_inicio' => $data_inicio,
                'data_fim' => $data_fim,
                'id_veiculo' => $id_veiculo,
                'modelo' => $modelo,
                'placa' => $placa,
                'km_atual' => $km_atual,
                'nome' => $nome
            ];
        }
    }

    mysqli_stmt_close($stmt);
    return $lista;
}
