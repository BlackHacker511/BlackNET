<?php
require_once '../session.php';
require_once APP_PATH . 'classes/Clients.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$clients = new BlackNET\Clients($database, $utils);

$logs = $clients->getLogs();

$spreadsheet = new Spreadsheet();

$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'Time');
$sheet->setCellValue('B1', 'Victim ID');
$sheet->setCellValue('C1', 'Message');
$sheet->setCellValue('D1', 'Status');

$i = 2;
foreach ($logs as $log) {
    $sheet->setCellValue("A" . $i, $log->time);
    $sheet->setCellValue("B" . $i, $log->vicid);
    $sheet->setCellValue("C" . $i, $log->message);
    $sheet->setCellValue("D" . $i, $log->type);
    $i++;
}

$writer = new Xlsx($spreadsheet);

$filename = 'System_Logs - ' .  date("Y-m-d") .  '.xlsx';

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename=' . $filename);
header('Cache-Control: max-age=0');

$writer->save('php://output');

$utils->redirect(SITE_URL . '/viewlogs.php');
