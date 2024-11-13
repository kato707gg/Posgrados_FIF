<?php
require('fpdf.php');

// Crear una clase personalizada que hereda de FPDF
class PDF extends FPDF
{
    // Función para el encabezado
    function Header()
    {
        // Posicionamiento de la primera imagen (izquierda)
        $this->Image('../libraries/logoFIF.png', 10, -10, 55); // Ajusta la ruta y el tamaño

        // Posicionamiento de la segunda imagen (derecha)
        $this->Image('../libraries/mcclogo.png', 140, 6, 55); // Ajusta la ruta y el tamaño
    }
}

// Crear instancia de la clase PDF personalizada
$pdf = new PDF();
$pdf->AddPage();

// Definir el interlineado deseado (altura de la celda)
$lineHeight = 6;  // Puedes ajustar este valor según tus necesidades

$pdf->Ln(30);

// Título principal del documento
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, $lineHeight, 'Campus Juriquilla a __ de ________ del 202_', 0, 1, 'R');
$pdf->Ln(10);

// Saludo
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, $lineHeight, utf8_decode('Dr. Julio Alejandro Romero González'), 0, 1);
$pdf->Cell(0, $lineHeight, utf8_decode('Coordinador del programa de la Maestría en Ciencias de la Computación'), 0, 1);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, $lineHeight, utf8_decode('P r e s e n t e'), 0, 1);
$pdf->Ln(10);

$pdf->SetFont('Arial', '', 12);

// Cuerpo principal
$pdf->MultiCell(0, $lineHeight, utf8_decode("Por este medio, le informamos que el comité académico del estudiante ___________________________\n"
                                . "con número de expediente___________, se reunió de manera virtual, para realizar la evaluación\n"
                                . "del avance del proyecto de investigación.\n\n"
                                . "De acuerdo con lo planteado en el cronograma de trabajo y a los requerimientos del _____ semestre\n"
                                . "del programa del Doctorado en Ciencias de la Computación del cual se tienen los siguientes resultados:"), 0, 'J');
$pdf->Ln($lineHeight);

// Secciones de evaluación
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, $lineHeight, utf8_decode('Sobre Avance gradual'), 0, 1);
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(0, $lineHeight, utf8_decode("Observaciones y recomendaciones:\n\n__________________________________________________________________\n"
                                . "__________________________________________________________________\n"
                                . "__________________________________________________________________\n\n"), 0, 'J');

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, $lineHeight, utf8_decode('Sobre entregable y resultados esperados de acuerdo con el semestre a evaluar (ver anexo)'), 0, 1);
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(0, $lineHeight, utf8_decode("Observaciones y recomendaciones:\n\n__________________________________________________________________\n"
                                . "__________________________________________________________________\n"
                                . "__________________________________________________________________\n\n"), 0, 'J');

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, $lineHeight, utf8_decode('Sobre el Avance del Proyecto'), 0, 1);
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(0, $lineHeight, utf8_decode("(Avances en la metodología y pertinencia en la estrategia experimental)\n\n"
                                . "Observaciones y recomendaciones:\n\n"
                                . "__________________________________________________________________\n"
                                . "__________________________________________________________________\n"
                                . "__________________________________________________________________\n\n"), 0, 'J');

// Calificación
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, $lineHeight, utf8_decode('Calificación ____________'), 0, 1);
$pdf->Ln($lineHeight);

// Comité
$pdf->Cell(0, $lineHeight, utf8_decode('INTEGRANTES DEL COMITÉ COMENTARIO INDIVIDUAL'), 0, 1);
$pdf->Ln($lineHeight);

for ($i = 0; $i < 4; $i++) {
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, $lineHeight, utf8_decode('Nombre y Firma ___________________________'), 0, 1);
    $pdf->Ln($lineHeight);
}

// Generar el PDF
$pdf->Output('I', 'ActaSeminarioTutoral.pdf'); // 'I' para mostrar en el navegador, 'F' para guardar en servidor
?>
