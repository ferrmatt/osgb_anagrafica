<?php

require_once('./function.php');
checkLogin();
session_start();
if ($_GET['type'] == 'soci')
    $title="Export - Ricerca Soci"; 
else if ($_GET['type'] == 'anagrafica')
    $title="Export - Anagrafica"; 
else if ($_GET['type'] == 'libro_soci')
    $title="Export - Libro Soci"; 
else
    $title="Generic Export";

require_once './PHPExcel/PHPExcel.php';
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("OSGB Merate")
        ->setTitle($title)
        ->setDescription("Esportato da Ricerca Soci");

$objPHPExcel->getActiveSheet()->fromArray($_SESSION['array_excel'], null, 'A1');
$objPHPExcel->getActiveSheet()->setTitle($title);

PHPExcel_Shared_Font::setAutoSizeMethod(PHPExcel_Shared_Font::AUTOSIZE_METHOD_EXACT);

$objPHPExcel->getActiveSheet()->getStyle("A1:Z1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle("A1:Z1")->getFill()->getStartColor()->setRGB('008000');
$objPHPExcel->getActiveSheet()->getStyle("A1:Z1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
$objPHPExcel->getActiveSheet()->getStyle("A1:Z1")->getFont()->setBold(true);

foreach(range('A','Z') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
        ->setAutoSize(true);
}

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
// We'll be outputting an excel file
header('Content-type: application/vnd.ms-excel');

// It will be called file.xls
header('Content-Disposition: attachment; filename='.$title.'.xls');
$objWriter->save('php://output');

?>

