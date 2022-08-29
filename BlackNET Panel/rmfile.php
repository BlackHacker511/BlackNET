<?php
require_once 'session.php';

$msg = "";
$id = "";
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $files = $_POST['file'];
    $vicid = $utils->sanitize($_POST['vicid']);
    if ($auth->checkToken($_POST['csrf'], $_SESSION['csrf'])) {
        foreach ($files as $file) {
            if (strpos($file, "../")) {
                $id = $vicid;
                $msg = "error";
            }
            $filename = $utils->sanitize($file);
            $real_path = realpath("upload" . "/" . $vicid . "/" . $filename);
            if (file_exists($real_path)) {
                unlink($real_path);
            } else {
                $id = $vicid;
                $msg = "error";
            }
        }
        $id = $vicid;
        $msg = "yes";
    } else {
        $id = $vicid;
        $msg = "csrf";
    }
} else {
    $file = $utils->sanitize($_GET['fname']);
    $vicid = $utils->sanitize($_GET['vicid']);
    if (strpos($file, "../")) {
        $id = $vicid;
        $msg = "error";
    }
    $filename = $utils->sanitize(stripcslashes($file));
    $real_path = realpath("upload" . "/" . $vicid . "/" . $filename);
    if ($auth->checkToken($_GET['csrf'], $_SESSION['csrf'])) {
        if (file_exists($real_path)) {
            unlink($real_path);
            $id = $vicid;
            $msg = "yes";
        } else {
            $id = $vicid;
            $msg = "error";
        }
    } else {
        $id = $vicid;
        $msg = "csrf";
    }
}

$utils->redirect(SITE_URL . "/viewuploads.php?vicid=" . $id . "&msg=" . $msg);
