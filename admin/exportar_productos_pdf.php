<?php

require_once(__DIR__ . '/../includes/conexion.php');

if (session_status() === PHP_SESSION_NONE)
    session_start();

// Solo admins pueden exportar
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

require_once(__DIR__ . '/../lib/fpdf.php');

// Obtener todos los productos de la base de datos
$stmt = $conexion->query("SELECT id_producto, nombre, categoria, precio, stock FROM productos ORDER BY id_producto ASC");
$productos = $stmt->fetchAll();

// Crear el documento PDF
$pdf = new FPDF('L', 'mm', 'A4');

$pdf->SetAutoPageBreak(true, 15);
$pdf->AddPage();

// ------- CABECERA DEL DOCUMENTO -------
$pdf->SetFont('Arial', 'B', 20);
$pdf->SetTextColor(33, 37, 41); // color #212529
$pdf->Cell(0, 15, utf8_decode('Catálogo de Productos - OtakuStore'), 0, 1, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(120, 120, 120);
$pdf->Cell(0, 6, utf8_decode('Exportado el ' . date('d/m/Y') . ' a las ' . date('H:i')), 0, 1, 'C');
$pdf->Cell(0, 6, utf8_decode('Total de productos: ' . count($productos)), 0, 1, 'C');
$pdf->Ln(8);

// ------- ENCABEZADO DE LA TABLA -------
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(255, 212, 71);

$pdf->SetTextColor(33, 37, 41);


$anchoID = 20;
$anchoNom = 110;
$anchoCat = 45;
$anchoPrecio = 40;
$anchoStock = 35;
$xInicio = ($pdf->GetPageWidth() - ($anchoID + $anchoNom + $anchoCat + $anchoPrecio + $anchoStock)) / 2;
$pdf->SetX($xInicio);

$pdf->Cell($anchoID, 10, 'ID', 1, 0, 'C', true);
$pdf->Cell($anchoNom, 10, 'Nombre', 1, 0, 'C', true);
$pdf->Cell($anchoCat, 10, utf8_decode('Categoría'), 1, 0, 'C', true);
$pdf->Cell($anchoPrecio, 10, utf8_decode('Precio (€)'), 1, 0, 'C', true);
$pdf->Cell($anchoStock, 10, 'Stock', 1, 1, 'C', true);

// ------- FILAS DE DATOS -------
$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(50, 50, 50);
$fill = false;

foreach ($productos as $p) {
    // Alternar color de fila
    if ($fill) {
        $pdf->SetFillColor(245, 245, 245);
    }
    else {
        $pdf->SetFillColor(255, 255, 255);
    }

    $pdf->SetX($xInicio);
    $pdf->Cell($anchoID, 8, $p['id_producto'], 1, 0, 'C', true);
    $pdf->Cell($anchoNom, 8, utf8_decode($p['nombre']), 1, 0, 'L', true);
    $pdf->Cell($anchoCat, 8, utf8_decode($p['categoria'] === 'Cómic' ? 'Manga' : 'Funko'), 1, 0, 'C', true);
    $pdf->Cell($anchoPrecio, 8, number_format(floatval($p['precio']), 2, ',', '.') . ' ' . chr(128), 1, 0, 'R', true);

    // Para stock agotado
    if (intval($p['stock']) <= 0) {
        $pdf->SetTextColor(200, 30, 30);
    }
    elseif (intval($p['stock']) <= 5) {
        $pdf->SetTextColor(200, 150, 0);
    }
    $pdf->Cell($anchoStock, 8, intval($p['stock']), 1, 1, 'C', true);
    $pdf->SetTextColor(50, 50, 50);

    $fill = !$fill;
}

// ------- PIE DEL DOCUMENTO -------
$pdf->Ln(10);
$pdf->SetFont('Arial', 'I', 9);
$pdf->SetTextColor(150, 150, 150);
$pdf->Cell(0, 6, utf8_decode('© ' . date('Y') . ' OtakuStore — Documento generado automáticamente'), 0, 0, 'C');

// Enviar PDF al navegador
$pdf->Output('I', 'Productos_OtakuStore_' . date('Ymd') . '.pdf');
