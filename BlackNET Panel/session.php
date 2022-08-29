<?php

session_start();
require_once 'config/config.php';
require_once APP_PATH . 'classes/Database.php';
require_once APP_PATH . 'classes/User.php';
require_once APP_PATH . 'classes/Auth.php';
require_once APP_PATH . 'classes/Utils.php';
require_once APP_PATH . 'classes/Template.php';

$database = new BlackNET\Database();

$utils = new BlackNET\Utils();

$user = new BlackNET\User($database);

$auth = new BlackNET\Auth($database, $utils);

$current_url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

if (isset($_SESSION)) {
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : null;

    if (!isset($_SESSION['loggedin'])) {
        $utils->redirect(SITE_URL . "/login.php");
    }

    if ($username != null) {
        $data = $user->getUserData($username);

        if (!isset($_SESSION['current_ip'])) {
            $_SESSION['current_ip'] = $utils->sanitize($_SERVER['REMOTE_ADDR']);
        }

        if (!(isset($_SESSION['csrf']))) {
            $auth->generateSessionToken();
        }

        if (isset($_SESSION['isHuman'])) {
            if ($_SESSION['isHuman'] == false) {
                $utils->redirect(SITE_URL . '/logout.php');
            }
        }

        if ($user->isTwoFAEnabled($username) == true) {
            if (!isset($_SESSION['OTP']) || $_SESSION['OTP'] != true) {
                $utils->redirect(SITE_URL . "/auth.php");
            }
        }

        // Roles Controller
        if (strpos($current_url, "/modules")) {
            if ($data->role != 1) {
                $utils->redirect(SITE_URL . "/index.php?action=modules&msg=forbidden");
            }
        }

        if (strpos($current_url, "/settings.php")) {
            if ($data->role != 1) {
                $utils->redirect(SITE_URL . "/index.php?action=settings&msg=forbidden");
            }
        }
    } else {
        $utils->redirect(SITE_URL . "/login.php");
    }
}
