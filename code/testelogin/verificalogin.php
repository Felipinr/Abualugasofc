<?php
require_once 'conexao.php';

$email_funcionario = $_POST['email'];
$senha_funcionario = $_POST['senha'];

$sqlFuncionario = "SELECT * FROM funcionarios WHERE email = '$email_funcionario' AND senha = '$senha_funcionario'";
$resultadoFuncionario = mysqli_query($conexao, $sqlFuncionario);

if (mysqli_num_rows($resultadoFuncionario) > 0) {
    session_start();
    $_SESSION['logado'] = true;
    header('Location: index.php');
    exit();
} else {
    header('Location: login.html');
    exit();
}