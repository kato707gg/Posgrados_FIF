<?php
$url = "https://www.timeapi.io/api/time/current/zone?timeZone=AMERICA%2FMEXICO_CITY";

// Obtener datos de la API
$response = file_get_contents($url);
$data = json_decode($response, true);

// Obtener la fecha actual en formato ISO
$fechaActual = $data['year']; // Ejemplo: "2024-11-19T12:34:56-06:00"
$fechaDate = $data['day'];

// Mostrar solo la fecha
$year = substr($fechaActual, 3, 1); // "2024-11-19"


function obtenerNombreMes($mesNum) {
    $meses = [
        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
        5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
        9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
    ];
    return $meses[(int)$mesNum];
}
$mesNum =  $data['month'];
$mesNombre =  obtenerNombreMes($mesNum);

if (session_status()== PHP_SESSION_NONE){
    session_start();
}
include ("../conexion.php");


$id_alumno = $_SESSION['id'];
$SQL = "SELECT d.nombre, d.a_paterno, d.a_materno, de.observacion
    FROM asignaciones a
    INNER JOIN evaluaciones ev ON a.exp_alumno = ev.exp_alumno
    INNER JOIN detalle_evaluaciones de ON ev.id = de.id_evaluacion
    INNER JOIN docentes d ON de.id_sinodo = d.clave
    WHERE a.exp_alumno = '$id_alumno'";


$SQL2= "SELECT c.nombre AS coor_nombre,
        c.a_paterno AS coor_paterno,  
        c.a_materno AS coor_materno, 
        e.nombre, e.a_paterno, e.a_materno,
        ev.cal_final
        FROM asignaciones a
        INNER JOIN coordinadores c ON a.clave_coordinador = c.clave
        INNER JOIN estudiantes e ON a.exp_alumno = e.exp
        INNER JOIN evaluaciones ev ON a.exp_alumno =  ev.exp_alumno
        WHERE a.exp_alumno = '$id_alumno'";

$Con = Conectar();
$Resultado = Ejecutar($Con, $SQL);
$Resultado2 = Ejecutar($Con, $SQL2);
if (mysqli_num_rows($Resultado2) > 0){
    $Fila = mysqli_fetch_array($Resultado2);
    $Nom_Coordinador =  $Fila['coor_nombre'] . " " . $Fila['coor_paterno'] . " " . $Fila['coor_materno'];
    $Nom_Alumno = $Fila['nombre'] . " " . $Fila['a_paterno'] . " " . $Fila['a_materno'];
    $Cal_Final = $Fila['cal_final'];

}


require('fpdf.php');

// Establecer márgenes (izquierdo, superior, derecho)





// Crear una clase personalizada que hereda de FPDF
class PDF extends FPDF
{
    // Función para el encabezado
    function Header()
    {
        // Posicionamiento de la primera imagen (izquierda)
        $this->Image('../libraries/logoFIF.png', 24, -10, 55); // Ajusta la ruta y el tamaño

        // Posicionamiento de la segunda imagen (derecha)
        $this->Image('../libraries/mcclogo.png', 134, 6, 55); // Ajusta la ruta y el tamaño
    }
}

// Crear instancia de la clase PDF personalizada
$pdf = new PDF();
$pdf->AddPage();
$pdf->SetMargins(25, 30, 25);
// Definir el interlineado deseado (altura de la celda)
$lineHeight = 6;  // Puedes ajustar este valor según tus necesidades

$pdf->Ln(30);

// Título principal del documento
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, $lineHeight, "Campus Juriquilla a $fechaDate de $mesNombre del 202$year", 0, 1, 'R');
$pdf->Ln(10);

// Saludo
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, $lineHeight, utf8_decode('Dr.'." ". $Nom_Coordinador), 0, 1);
$pdf->Cell(0, $lineHeight, utf8_decode('Coordinador del programa de la Maestría en Ciencias de la Computación'), 0, 1);
$pdf-> Ln(12);
$pdf->SetFont('Arial', 'B', 12);

$pdf->Cell(0, $lineHeight, utf8_decode('P r e s e n t e.'), 0, 1);
$pdf->Ln(10);

$pdf->SetFont('Arial', '', 12);

// Cuerpo principal
$pdf->MultiCell(0, $lineHeight, utf8_decode("Por este medio, le informamos que el comité académico del estudiante $Nom_Alumno\n"
                                . "con número de expediente $id_alumno, se reunió de manera virtual, para realizar la evaluación\n"
                                . "del avance del proyecto de investigación.\n\n"
                                . "De acuerdo con lo planteado en el cronograma de trabajo y a los requerimientos del _____ semestre\n"
                                . "del programa del Doctorado en Ciencias de la Computación del cual se tienen los siguientes resultados:"), 0, 'J');
$pdf->Ln($lineHeight);

// Secciones de evaluación
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, $lineHeight, utf8_decode('Sobre Avance gradual'), 0, 1);
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(0, $lineHeight, utf8_decode("Observaciones y recomendaciones:\n\n__________________________________________________________________\n"
                                . "__________________________________________________________________\n". 
                                "__________________________________________________________________\n"
                                ."__________________________________________________________________\n"
                                . "__________________________________________________________________\n\n"), 0, 'J');

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, $lineHeight, utf8_decode("Sobre entregable y resultados esperados de acuerdo con el semestre a evaluar"), 0, 1);
$pdf->Cell(0,$lineHeight, utf8_decode("(ver anexo)"),0,1);
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(0, $lineHeight, utf8_decode("Observaciones y recomendaciones:\n\n__________________________________________________________________\n"
                                . "__________________________________________________________________\n"
                                . "__________________________________________________________________\n"
                                . "__________________________________________________________________\n"
                                . "__________________________________________________________________\n\n\n\n\n\n"), 0, 'J');



$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, $lineHeight, utf8_decode('Sobre el Avance del Proyecto'), 0, 1);
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(0, 6, utf8_decode("(Avances en la metodología y pertinencia en la estrategia experimental)\n\n"
                                . "Observaciones y recomendaciones:\n\n"
                                . "__________________________________________________________________\n"
                                . "__________________________________________________________________\n"
                                . "__________________________________________________________________\n\n"), 0, 'J');

                            

$pdf->Ln(10);                                // Calificación
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, $lineHeight, utf8_decode("Calificación: $Cal_Final"), 0, 1);
$pdf->Ln($lineHeight);

// Comité
$pdf->SetFont('Arial', 'B', 12);
$columna =80;
$pdf->Cell($columna, $lineHeight, utf8_decode('INTEGRANTES DEL COMITÉ'),1,0, 'C');
$pdf->Cell($columna, $lineHeight, utf8_decode('COMENTARIO INDIVIDUAL'), 1, 1, 'C');




$colWidth = 80; // Ancho de cada columna
$lineHeight = 35; // Altura de la celda

// Bucle para 4 filas de la tabla
for ($i = 0; $i < 4; $i++) {
    // Columna 1: Nombre y espacio para firma
    $x1 = $pdf->GetX(); // Obtener la posición actual en X
    $y1 = $pdf->GetY(); // Obtener la posición actual en Y
    
    // Dibujar celda de la primera columna
    $pdf->Cell($colWidth, $lineHeight, '', 1, 0); // Celda vacía con borde
    
    // Posicionar el texto en la parte inferior de la celda (nombre y firma)
    $pdf->SetXY($x1, $y1 + $lineHeight - 8); // Ajustar 8 unidades desde el fondo
    $pdf->Cell($colWidth, 8, utf8_decode('Nombre y Firma'), 0, 0, 'C');
    
    // Columna 2: Observaciones (sin texto)
    $pdf->SetXY($x1 + $colWidth, $y1); // Ajustar X para dibujar la segunda celda en la misma fila
    $pdf->Cell($colWidth, $lineHeight, '', 1, 0); // Celda vacía con borde
    
    // Restablecer la posición para la próxima fila
    $pdf->Ln($lineHeight); // Mover a la siguiente línea después de ambas celdas
}


// Generar el PDF
$pdf->Output('I', 'ActaSeminarioTutoral.pdf'); // 'I' para mostrar en el navegador, 'F' para guardar en servidor
?>
