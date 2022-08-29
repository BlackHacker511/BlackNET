<?php

$upload = new BlackUpload\Upload();

$vicID = isset($_GET['vicid']) ? $utils->sanitize($_GET['vicid']) : '';

$blacklist = array('..', '.', "index.html", ".htaccess");

$files = [];

if (file_exists("upload/$vicID")) {
    $user_files = scandir("upload/$vicID");
    foreach ($user_files as $file) {
        if (!in_array($file, $blacklist)) {
            $files[] = $file;
        }
    }
}

$disabled = (empty($files)) ? "disabled" : null;

$page = "viewUploadsPage";
