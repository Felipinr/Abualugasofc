<?php require_once 'TCPDF-main/tcpdf.php'; 
require_once 'conexao.php';
require_once 'core.php';
require_once 'login2.php';

$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Carromeu e Julieta');
$pdf->SetTitle('Lista de Veículos');
$pdf->SetHeaderData('', 0, 'Lista de Veículos', 'Carromeu e Julieta');
$pdf->setHeaderFont(['helvetica', '', 12]);
$pdf->setFooterFont(['helvetica', '', 10]);
$pdf->SetMargins(15, 27, 15);
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(10);
$pdf->SetAutoPageBreak(TRUE, 25);
$pdf->setFont('helvetica', '', 10);
$pdf->AddPage();

$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Lista de Veículos', 0, 1, 'C');
$pdf->Ln(10);

$pdf->SetFont('helvetica', 'B', 12);
$html = '
<table border="1" cellpadding="4">
    <thead>
        <tr>
            <th><b>ID</b></th>
            <th><b>Modelo</b></th>
            <th><b>Marca</b></th>
            <th><b>Ano</b></th>
            <th><b>Placa</b></th>
            <th><b>Cor</b></th>
            <th><b>Km Atual</b></th>
            <th><b>Disponibilidade</b></th>
        </tr>
    </thead>
    <tbody>';

$resultados = listarCarros($conexao);

foreach ($resultados as $modelo) {
    $html .= '<tr>
        <td>' . htmlspecialchars($modelo[0]) . '</td>
        <td>' . htmlspecialchars($modelo[1]) . '</td>
        <td>' . htmlspecialchars($modelo[2]) . '</td>
        <td>' . htmlspecialchars($modelo[3]) . '</td>
        <td>' . htmlspecialchars($modelo[4]) . '</td>
        <td>' . htmlspecialchars($modelo[5]) . '</td>
        <td>' . htmlspecialchars($modelo[6]) . '</td>
        <td>Disponível</td> <!-- Valor fixo -->
    </tr>';
}

$html .= '</tbody></table>';

$pdf->writeHTML($html, true, false, true, false, '');

$pdf->Output('lista_veiculos.pdf', 'I');
?>