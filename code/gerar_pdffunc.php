<?php
require_once 'TCPDF-main/tcpdf.php'; 
require_once 'conexao.php';
require_once 'core.php';

/**
 * Gera um PDF com a lista de funcionários cadastrados.
 *
 * Esta função utiliza a biblioteca TCPDF para gerar um arquivo PDF contendo uma tabela com as informações
 * dos funcionários cadastrados no banco de dados. Os dados são recuperados da função listarFuncionarios().
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
$pdf->SetTitle('Lista de Funcionários');
$pdf->SetHeaderData('', 0, 'Lista de Funcionários', 'Carromeu e Julieta');
$pdf->setHeaderFont(['helvetica', '', 12]);
$pdf->setFooterFont(['helvetica', '', 10]);
$pdf->SetMargins(15, 27, 15);
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(10);
$pdf->SetAutoPageBreak(TRUE, 25);
$pdf->setFont('helvetica', '', 10);

// Adiciona uma página ao documento PDF
$pdf->AddPage();

// Título da lista de funcionários
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Lista de Funcionários', 0, 1, 'C');
$pdf->Ln(10);

// Define o estilo da tabela de dados
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

// Recupera os dados dos funcionários da função listarFuncionarios
$resultados = listarFuncionarios($conexao);

// Preenche a tabela com os dados dos funcionários
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

// Escreve o HTML no documento PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Exibe o PDF gerado
$pdf->Output('lista_funcionarios.pdf', 'I');
?>
