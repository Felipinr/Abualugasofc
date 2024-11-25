<?php
/**
 * Protege a página de dashboard, garantindo que apenas usuários autenticados possam acessá-la.
 * 
 * Se o usuário não estiver autenticado, será redirecionado para a página de login. Caso contrário,
 * a página de dashboard será exibida, fornecendo acesso ao conteúdo protegido.
 *
 * @param void
 * @return void
 */

session_start();

// Verifica se o usuário está autenticado
if (!isset($_SESSION['funcionario_autenticado'])) {
    // Redireciona para a página de login caso o usuário não esteja autenticado
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
    <h2>Bem-vindo ao painel de controle</h2>
    <p>Esta página é protegida e só pode ser acessada por funcionários autenticados.</p>
    <a href="logout.php">Sair</a>
</body>
</html>