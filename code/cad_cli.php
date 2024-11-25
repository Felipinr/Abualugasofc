<?php

require_once 'conexao.php';
require_once 'login2.php';

/**
 * Função para cadastrar um novo cliente no banco de dados.
 *
 * @param string        $nome                  Nome completo do cliente.
 * @param string        $cpf_cnpj              CPF ou CNPJ do cliente.
 * @param string        $endereco              Endereço completo do cliente.
 * @param string        $telefone              Número de telefone do cliente.
 * @param string        $email                 Endereço de e-mail do cliente.
 * @param string        $carteira_motorista    Número da carteira de motorista do cliente (se aplicável).
 * @param string        $validade_carteira     Validade da carteira de motorista do cliente.
 * @param string        $fisico_juridico       Indica se o cliente é pessoa física ou jurídica.
 * @return void
 */

 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
    $nome = $_POST['nome'];
    $cpf_cnpj = $_POST['cpf_cnpj'];
    $endereco = $_POST['endereco'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $carteira_motorista = $_POST['carteira_motorista'];
    $validade_carteira = $_POST['validade_carteira'];
    $fisico_juridico = $_POST['fisico_juridico'];


    $sql = "INSERT INTO clientes (nome, cpf_cnpj, endereco, telefone, email, carteira_motorista, validade_carteira, fisico_juridico) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = mysqli_prepare($conexao, $sql)) {
       
        mysqli_stmt_bind_param($stmt, "ssssssss", $nome, $cpf_cnpj, $endereco, $telefone, $email, $carteira_motorista, $validade_carteira, $fisico_juridico);
        
        mysqli_stmt_execute($stmt);
        
        mysqli_stmt_close($stmt);
    } else {

        echo "Erro na preparação da consulta: " . mysqli_error($conexao);
    }

    header("Location: cadastrocerto.html");
    exit(); 
}
?>
