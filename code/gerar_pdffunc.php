<?php
require_once 'TCPDF-main/tcpdf.php'; // Inclua o TCPDF
require_once 'conexao.php';
require_once 'core.php';

// Criação de uma nova instância do TCPDF
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// Configurações do PDF
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Carromeu e Julieta');
$pdf->SetTitle('Lista de Funcionários');
$pdf->SetHeaderData('', 0, 'Lista de Funcionários', 'Carromeu e Julieta');
$pdf->setHeaderFont(['helvetica', '', 12]);
$pdf->setFooterFont(['helvetica', '', 10]);
$pdf->SetMargins(15, 27, 15);
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(10);
$pdf->SetAutoPageBreak(TRUE, 25);
$pdf->setFont('helvetica', '', 10);
$pdf->AddPage();

// Adiciona um título ao PDF
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Lista de Funcionários', 0, 1, 'C');
$pdf->Ln(10);

// Cabeçalho da tabela
$pdf->SetFont('helvetica', 'B', 12);
$html = '
<table border="1" cellpadding="4">
    <thead>
        <tr>
            <th><b>ID do Funcionário</b></th>
            <th><b>Nome</b></th>
            <th><b>CPF</b></th>
            <th><b>Telefone</b></th>
            <th><b>E-mail</b></th>
        </tr>
    </thead>
    <tbody>';

// Busca os dados dos funcionários no banco de dados
$resultados = listarFuncionarios($conexao);

foreach ($resultados as $funcionario) {
    $html .= '<tr>
        <td>' . htmlspecialchars($funcionario[0]) . '</td>
        <td>' . htmlspecialchars($funcionario[1]) . '</td>
        <td>' . htmlspecialchars($funcionario[2]) . '</td>
        <td>' . htmlspecialchars($funcionario[3]) . '</td>
        <td>' . htmlspecialchars($funcionario[4]) . '</td>
    </tr>';
}

$html .= '</tbody></table>';

// Adiciona a tabela ao PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Saída do PDF
$pdf->Output('lista_funcionarios.pdf', 'I');