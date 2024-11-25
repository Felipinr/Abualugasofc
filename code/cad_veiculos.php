<?php

require_once 'conexao.php';

/**
 * Função para cadastrar um novo veículo no banco de dados.
 *
 * @param string    $modelo                 Modelo do veículo.
 * @param string    $marca                  Marca do veículo.
 * @param int       $ano                    Ano de fabricação do veículo.
 * @param string    $placa                  Placa do veículo.
 * @param string    $cor                    Cor do veículo.
 * @param int       $km_atual               Quilometragem atual do veículo.
 * @param bool      $airbag                 Indica se o veículo possui airbag (1 para sim, 0 para não).
 * @param int       $num_bancos             Número de bancos do veículo.
 * @param int       $num_portas             Número de portas do veículo.
 * @param string    $combustivel            Tipo de combustível utilizado pelo veículo.
 * @param string    $cambio                 Tipo de câmbio do veículo.
 * @param bool      $ar_condicionado        Indica se o veículo possui ar condicionado (1 para sim, 0 para não).
 * @param string    $direcao                Tipo de direção do veículo (ex: "hidráulica", "elétrica").
 * @param bool      $som                    Indica se o veículo possui sistema de som (1 para sim, 0 para não).
 * @param bool      $bluetooth              Indica se o veículo possui Bluetooth (1 para sim, 0 para não).
 * @param bool      $gps                    Indica se o veículo possui GPS (1 para sim, 0 para não).
 * @param bool      $sensor_estacionamento  Indica se o veículo possui sensor de estacionamento (1 para sim, 0 para não).
 * @param bool      $camera_re              Indica se o veículo possui câmera de ré (1 para sim, 0 para não).
 * @param bool      $disponivel             Indica se o veículo está disponível para aluguel (1 para sim, 0 para não).
 * @return void
 */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $modelo = $_POST['modelo'];
    $marca = $_POST['marca'];
    $ano = $_POST['ano'];
    $placa = $_POST['placa'];
    $cor = $_POST['cor'];
    $km_atual = $_POST['km_atual'];
    $airbag = isset($_POST['airbag']) ? 1 : 0;
    $num_bancos = $_POST['num_bancos'];
    $num_portas = $_POST['num_portas'];
    $combustivel = $_POST['combustivel'];
    $cambio = $_POST['cambio'];
    $ar_condicionado = isset($_POST['ar_condicionado']) ? 1 : 0;
    $direcao = $_POST['direcao'];
    $som = isset($_POST['som']) ? 1 : 0;
    $bluetooth = isset($_POST['bluetooth']) ? 1 : 0;
    $gps = isset($_POST['gps']) ? 1 : 0;
    $sensor_estacionamento = isset($_POST['sensor_estacionamento']) ? 1 : 0;
    $camera_re = isset($_POST['camera_re']) ? 1 : 0;
    $disponivel = isset($_POST['disponivel']) ? 1 : 0;

    // Comando SQL para inserir os dados do veículo na tabela "veiculos"
    $sql = "INSERT INTO veiculos (modelo, marca, ano, placa, cor, km_atual, airbag, num_bancos, num_portas, combustivel, cambio, ar_condicionado, direcao, som, bluetooth, gps, sensor_estacionamento, camera_re, disponivel)
            VALUES ('$modelo', '$marca', '$ano', '$placa', '$cor', '$km_atual', '$airbag', '$num_bancos', '$num_portas', '$combustivel', '$cambio', '$ar_condicionado', '$direcao', '$som', '$bluetooth', '$gps', '$sensor_estacionamento', '$camera_re', '$disponivel')";

    // Executando a query
    mysqli_query($conexao, $sql);

    // Redirecionando após a execução bem-sucedida
    header("Location: cadastrocerto.html");
}
?>
