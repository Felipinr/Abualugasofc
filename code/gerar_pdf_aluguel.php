<?php
/**
 * Gera um PDF com os detalhes de um aluguel.
 *
 * Este script é responsável por gerar um PDF contendo os detalhes de um aluguel específico.
 * O script valida se o ID do aluguel foi enviado via GET e, em caso afirmativo, recupera os dados
 * do aluguel a partir do banco de dados e gera o PDF utilizando a biblioteca TCPDF.
 * 
 * O script segue os seguintes passos:
 * 1. Validação do ID do aluguel enviado via GET.
 * 2. Recuperação dos dados do aluguel a partir do banco de dados.
 * 3. Geração do PDF com os detalhes do aluguel.
 * 4. Exibição do PDF gerado.
 *
 * @param mysqli      $conexao           Conexão com o banco de dados.
 * @param int         $id_aluguel        ID do aluguel a ser exibido. Este valor é fornecido via parâmetro `$_GET['id_aluguel']`.
 * @param string      $request_method    Tipo da requisição HTTP. Verifica se a requisição é do tipo GET para recuperar os dados.
 * @return void
 */

require_once 'TCPDF-main/tcpdf.php'; 
require_once 'conexao.php';

if (!isset($_GET['id_aluguel'])) {
    die('ID do aluguel não fornecido.');
}

$id_aluguel = intval($_GET['id_aluguel']);

$sql = "
    SELECT 
        a.id_aluguel, 
        f.nome AS funcionario, 
        c.nome AS cliente, 
        a.data_inicio, 
        a.data_fim, 
        a.valor_km
    FROM alugueis a
    JOIN funcionarios f ON a.id_funcionario = f.id_funcionario
    JOIN clientes c ON a.id_cliente = c.id_cliente
    WHERE a.id_aluguel = ?";
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, 'i', $id_aluguel);

if (!mysqli_stmt_execute($stmt)) {
    die('Erro ao buscar dados do aluguel: ' . mysqli_stmt_error($stmt));
}

mysqli_stmt_bind_result($stmt, $id_aluguel, $funcionario, $cliente, $data_inicio, $data_fim, $valor_km);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

if (!$id_aluguel) {
    die('Aluguel não encontrado.');
}

$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Sistema de Aluguel');
$pdf->SetTitle('Detalhes do Aluguel');
$pdf->SetMargins(15, 15, 15);
$pdf->AddPage();

// Adicionar conteúdo ao PDF
$html = "
<h1>Detalhes do Aluguel</h1>
<p><strong>ID do Aluguel:</strong> $id_aluguel</p>
<p><strong>Funcionário:</strong> $funcionario</p>
<p><strong>Cliente:</strong> $cliente</p>
<p><strong>Data de Início:</strong> $data_inicio</p>
<p><strong>Data de Fim:</strong> $data_fim</p>
<p><strong>Valor por KM:</strong> $valor_km</p>
";

$pdf->writeHTML($html, true, false, true, false, '');

// Exibir o PDF
$pdf->Output('detalhes_aluguel.pdf', 'I');
?><?php
require_once 'TCPDF-main/tcpdf.php'; 
require_once 'conexao.php';

if (!isset($_GET['id_aluguel'])) {
    die('ID do aluguel não fornecido.');
}

$id_aluguel = intval($_GET['id_aluguel']);

$sql = "
    SELECT 
        a.id_aluguel, 
        f.nome AS funcionario, 
        c.nome AS cliente, 
        a.data_inicio, 
        a.data_fim, 
        a.valor_km
    FROM alugueis a
    JOIN funcionarios f ON a.id_funcionario = f.id_funcionario
    JOIN clientes c ON a.id_cliente = c.id_cliente
    WHERE a.id_aluguel = ?";
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, 'i', $id_aluguel);

if (!mysqli_stmt_execute($stmt)) {
    die('Erro ao buscar dados do aluguel: ' . mysqli_stmt_error($stmt));
}

mysqli_stmt_bind_result($stmt, $id_aluguel, $funcionario, $cliente, $data_inicio, $data_fim, $valor_km);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

if (!$id_aluguel) {
    die('Aluguel não encontrado.');
}

$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Sistema de Aluguel');
$pdf->SetTitle('Detalhes do Aluguel');
$pdf->SetMargins(15, 15, 15);
$pdf->AddPage();

$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Detalhes do Aluguel', 0, 1, 'C');

$pdf->SetFont('helvetica', '', 12);
$html = "
    <h2>Informações do Aluguel</h2>
    <p><strong>ID do Aluguel:</strong> {$id_aluguel}</p>
    <p><strong>Funcionário:</strong> {$funcionario}</p>
    <p><strong>Cliente:</strong> {$cliente}</p>
    <p><strong>Data de Início:</strong> {$data_inicio}</p>
    <p><strong>Data de Fim:</strong> {$data_fim}</p>
    <p><strong>Valor por KM:</strong> R$ " . number_format($valor_km, 2, ',', '.') . "</p>
";
$pdf->writeHTML($html);

$pdf->Output('detalhes_aluguel.pdf', 'I');
?>
