<?php

require_once 'TCPDF-main/tcpdf.php';
require_once 'conexao.php';
require_once 'core.php';

/**
 * Gera um PDF com a lista de clientes cadastrados.
 *
 * Esta função utiliza a biblioteca TCPDF para gerar um arquivo PDF contendo uma tabela com as informações
 * dos clientes cadastrados no banco de dados. Os dados são recuperados da função listarClientes().
 *
 * @require_once 'TCPDF-main/tcpdf.php'  Arquivo da biblioteca TCPDF.
 * @require_once 'conexao.php'            Conexão com o banco de dados.
 * @require_once 'core.php'               Funções auxiliares do sistema.
 *
 * @param PDO    $conexao                 Objeto de conexão com o banco de dados.
 * @return void
 */

// Cria uma nova instância do objeto TCPDF
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// Configurações do PDF
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Carromeu e Julieta');
$pdf->SetTitle('Lista de Clientes');
$pdf->SetHeaderData('', 0, 'Lista de Clientes', 'Carromeu e Julieta');
$pdf->setHeaderFont(['helvetica', '', 12]);
$pdf->setFooterFont(['helvetica', '', 10]);
$pdf->SetMargins(15, 27, 15);
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(10);
$pdf->SetAutoPageBreak(TRUE, 25);
$pdf->setFont('helvetica', '', 10);

// Adiciona uma página ao documento PDF
$pdf->AddPage();

// Título da lista de clientes
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Lista de Clientes', 0, 1, 'C');
$pdf->Ln(10);

// Define o estilo da tabela de dados
$pdf->SetFont('helvetica', 'B', 12);
$html = '
<table border="1" cellpadding="4">
    <thead>
        <tr>
            <th><b>ID</b></th>
            <th><b>Nome</b></th>
            <th><b>CPF/CNPJ</b></th>
            <th><b>Endereço</b></th>
            <th><b>Telefone</b></th>
            <th><b>E-mail</b></th>
            <th><b>Carteira de Motorista</b></th>
            <th><b>Validade da Carteira</b></th>
        </tr>
    </thead>
    <tbody>';

// Recupera os dados dos clientes da função listarClientes
$resultados = listarClientes($conexao);

// Preenche a tabela com os dados dos clientes
foreach ($resultados as $cliente) {
    $html .= '<tr>
        <td>' . htmlspecialchars($cliente[0]) . '</td>
        <td>' . htmlspecialchars($cliente[1]) . '</td>
        <td>' . htmlspecialchars($cliente[2]) . '</td>
        <td>' . htmlspecialchars($cliente[3]) . '</td>
        <td>' . htmlspecialchars($cliente[4]) . '</td>
        <td>' . htmlspecialchars($cliente[5]) . '</td>
        <td>' . htmlspecialchars($cliente[6]) . '</td>
        <td>' . htmlspecialchars($cliente[7]) . '</td>
    </tr>';
}

$html .= '</tbody></table>';

// Escreve o HTML no documento PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Exibe o PDF gerado
$pdf->Output('lista_clientes.pdf', 'I');
