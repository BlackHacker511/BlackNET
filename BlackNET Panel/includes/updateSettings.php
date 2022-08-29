<?php
require_once  '../session.php';
require_once APP_PATH . 'classes/Settings.php';
require_once APP_PATH . 'classes/Mailer.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $status = null;
    $settings = new BlackNET\Settings($database);
    if ($auth->checkToken($_POST['csrf'], $_SESSION['csrf']) == false) {
        $status = "csrf";
    } else {
        $recaptchaprivate = $utils->sanitize($_POST['recaptchaprivate']);
        $recaptchapublic = $utils->sanitize($_POST['recaptchapublic']);
        $recaptcha_status = isset($_POST['recaptcha_status']) ? 1 : 0;
        $panel_status = isset($_POST['panel_status']) ? 1 : 0;
        $smtp_status = isset($_POST['smtp_status']) ? 1 : 0;
        $delete_backup = isset($_POST['delete_backup']) ? 1 : 0;

        $settings_array = [
            "panel_status" => $panel_status,
            "delete_backup" => $delete_backup,
            "recaptcha_status" => $recaptcha_status,
            "recaptchapublic" => $recaptchapublic,
            "recaptchaprivate" => $recaptchaprivate,
            "smtp_host" => $utils->sanitize($_POST['smtp_host']),
            "smtp_username" => $utils->sanitize($_POST['smtp_username']),
            "smtp_password" => base64_encode($utils->sanitize($_POST['smtp_password'])),
            "smtp_port" => $utils->sanitize($_POST['smtp_port']),
            "smtp_security" => $utils->sanitize($_POST['smtp_security']),
            "smtp_status" => $smtp_status,
        ];

        $settings->updateSettings($settings_array);

        $status = "yes";
    }

    $utils->redirect(SITE_URL . "/settings.php?msg=" . $utils->sanitize($status));
}
