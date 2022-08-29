<?php

include '../session.php';
require_once APP_PATH . 'classes/Settings.php';

$settings = new BlackNET\Settings($database);
$vicid = $utils->sanitize($_GET['vicid']);
$rootPath = realpath(APP_PATH . "upload/" . $vicid);
$backupPath = APP_PATH . "backups/" . $vicid . '_'  . date("Y_m_d_H_i_s", time()) . '.zip';

$zip = new ZipArchive();
$zip->open($backupPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($rootPath),
    RecursiveIteratorIterator::LEAVES_ONLY
);

foreach ($files as $name => $file) {
    if (!$file->isDir()) {
        if (!(strpos($name, "index.html"))) {
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($rootPath) + 1);

            $zip->addFile($filePath, $relativePath);
        }
    }
}

$zip->close();

if ($backupPath && is_readable(realpath($backupPath))) {
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment;filename=' . $vicid . "." . 'zip');
    header('Cache-Control: max-age=0');
    readfile(realpath($backupPath));
}

if ($settings->getSettingValue("delete_backup") == true) {
    unlink(realpath($backupPath));
}
