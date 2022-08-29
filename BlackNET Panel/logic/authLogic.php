<?php

$page = "authPage";

$utils = new BlackNET\Utils();

$database = new BlackNET\Database();

$auth = new BlackNET\Auth($database, $utils);

$user = new BlackNET\User($database);

// Check if the user is loggedin
if (!isset($_SESSION['loggedin'])) {
    $utils->redirect(SITE_URL . "/logout.php");
} elseif (isset($_SESSION['OTP']) && ($_SESSION['OTP'] == true)) {
    $utils->redirect(SITE_URL . "/index.php");
} else {
    $_SESSION['OTP'] = false;
}

$uniqueid = $auth->generateDeviceID();

if ($auth->checkDeviceId($uniqueid) == true) {
    session_regenerate_id();

    $_SESSION['OTP'] = true;

    $utils->redirect(SITE_URL . "/index.php");
}

$g = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $code = $utils->sanitize($_POST['AuthCode']);
    $secret = $user->getSecret($_SESSION['username']);
    if ($g->checkCode($secret, $code)) {
        if (isset($_POST['remberme'])) {
            if (!isset($_COOKIE['2fa'])) {
                $utils->createCookie("2fa", true);
                $utils->createCookie("device_id", $uniqueid);
            }
        }

        session_regenerate_id();

        $_SESSION['OTP'] = true;

        $utils->redirect(SITE_URL . "/index.php");
    } else {
        $error = "Verification code is incorrect!!";
    }
}
