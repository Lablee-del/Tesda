<?php
require 'config.php';
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

if (!isset($_GET['ris_id'])) { die("No RIS ID provided."); }

$ris_id = (int)$_GET['ris_id'];
$ris = $conn->query("SELECT * FROM ris WHERE ris_id = $ris_id")->fetch_assoc();
$items = $conn->query("SELECT * FROM ris_items WHERE ris_id = $ris_id");

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Title
$sheet->mergeCells('A1:H1');
$sheet->setCellValue('A1', 'REQUISITION AND ISSUE SLIP');
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A1')->getFont()->setBold(true);

// RIS details
$sheet->setCellValue('A3', 'Entity Name : ' . $ris['entity_name']);
$sheet->setCellValue('E3', 'Fund Cluster : ' . $ris['fund_cluster']);
$sheet->setCellValue('A4', 'Division : ' . $ris['division']);
$sheet->setCellValue('A5', 'Office : ' . $ris['office']);
$sheet->setCellValue('E4', 'Responsibility Center Code : ' . $ris['responsibility_center_code']);
$sheet->setCellValue('E5', 'RIS No. : ' . $ris['ris_no']);

// Section headers
$sheet->mergeCells('A7:D7')->setCellValue('A7', 'Requisition');
$sheet->mergeCells('E7:F7')->setCellValue('E7', 'Stock Available?');
$sheet->mergeCells('G7:H7')->setCellValue('G7', 'Issue');

$sheet->fromArray(['Stock No.', 'Unit', 'Description', 'Quantity', 'Yes', 'No', 'Quantity', 'Remarks'], null, 'A8');

// Items
$row = 9;
foreach ($items as $item) {
    $sheet->setCellValue("A{$row}", $item['stock_number']);
    $sheet->setCellValue("B{$row}", $item['unit']);
    $sheet->setCellValue("C{$row}", $item['description']);
    $sheet->setCellValue("D{$row}", $item['requested_quantity']);
    $sheet->setCellValue("E{$row}", $item['stock_available'] == 'Yes' ? '✔' : '');
    $sheet->setCellValue("F{$row}", $item['stock_available'] == 'No' ? '✔' : '');
    $sheet->setCellValue("G{$row}", $item['issued_quantity']);
    $sheet->setCellValue("H{$row}", $item['remarks']);
    $row++;
}

// Add blank rows
while ($row <= 20) {
    $sheet->setCellValue("A{$row}", '');
    $row++;
}

// Purpose
$sheet->mergeCells("A22:H22")->setCellValue("A22", 'Purpose: ' . $ris['purpose']);

// Signatories
$sheet->mergeCells('B24:C24')->setCellValue('B24', 'Requested by:');
$sheet->mergeCells('D24:E24')->setCellValue('D24', 'Approved by:');
$sheet->mergeCells('F24:G24')->setCellValue('F24', 'Issued by:');
$sheet->mergeCells('H24:I24')->setCellValue('H24', 'Received by:');

// Borders
$sheet->getStyle("A7:H20")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
$sheet->getStyle("A22:H22")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
$sheet->getStyle("A24:H28")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

// Output the Excel file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="RIS_' . $ris['ris_no'] . '.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
