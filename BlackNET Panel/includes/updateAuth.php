<?php
require_once  '../session.php';

$g = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();
$status = isset($_POST['enable']) ? 1 : 0;
$code = isset($_POST['code']) ? $utils->sanitize($_POST['code']) : null;
$secret = isset($_POST['secret']) ? $utils->sanitize($_POST['secret']) : null;
$username = $utils->sanitize($_SESSION['username']);
$msg = [];

if ($auth->checkToken($_POST['csrf'], $_SESSION['csrf']) == false) {
    $msg = ["msg" => "error", "status" => "csrf"];
} else {

    if ($status == false) {
        $user->disable2fa($username);

        if (isset($_COOKIE['2fa'])) {
            $utils->deleteCookie("2fa");
            $utils->deleteCookie("device_id");
        }

        $msg = ["msg" => "code", "status" => "disable"];
    } else {
        if ($code != null) {
            if ($g->checkCode($secret, $code)) {
                $user->enables2fa($username, $secret);
                $msg = ["msg" => "code", "status" => "enable"];
            } else {
                $msg = ["msg" => "code", "status" => "error"];
            }
        } else {
            $msg = ['msg' => "code", "status" => "empty"];
        }
    }
}
$utils->redirect(
    SITE_URL .
        "/authsettings.php?msg=" .
        $utils->sanitize($msg['msg']) .
        "&status=" .
        $utils->sanitize($msg['status'])
);
