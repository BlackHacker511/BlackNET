<?php
require_once 'config/config.php';
require_once APP_PATH . 'classes/Utils.php';
require_once APP_PATH . 'classes/blackupload/Upload.php';

$utils = new BlackNET\Utils();

if (isset($_GET['id'])) {
    $client_folder = "upload/" . $utils->sanitize($utils->base64DecodeUrl($_GET['id']));
}

$upload_folder_array = [
    "folder_name" => $client_folder,
    "folder_path" => realpath($client_folder)
];

$upload = new BlackUpload\Upload($_FILES['file'], $upload_folder_array, "classes/blackupload/");

$upload->enableProtection();

if (
    $upload->checkForbidden() &&
    $upload->checkExtension() &&
    $upload->checkMime()
) {
    $upload->upload();
}
