<?php
require_once 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];

    $sql = "INSERT INTO funcionarios (nome, cpf, telefone, email) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexao, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'ssss', $nome, $cpf, $telefone, $email);
        
        if (mysqli_stmt_execute($stmt)) {

            header("Location: cadastrocerto.html");
            exit();
        } else {
            echo "Erro ao executar a consulta: " . mysqli_stmt_error($stmt);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Erro na preparação da consulta: " . mysqli_error($conexao);
    }

    mysqli_close($conexao);
}
?>
