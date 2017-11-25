<?php
require('libraries/fpdf/fpdf.php');

$pdf = new FPDF('P','mm','A5');
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(40,10,'Hello World!',1);
$pdf->Cell(60,10,'Powered by FPDF.',0,1,'C');
$pdf->Output();
?>