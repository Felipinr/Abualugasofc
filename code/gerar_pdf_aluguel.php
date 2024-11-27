<?php
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
