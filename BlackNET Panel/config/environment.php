<?php

define('ENVIRONMENT', 'installation');
ini_set("memory_limit", "1024M");

switch (ENVIRONMENT) {
    case 'development':
        error_reporting(-1);
        ini_set("display_errors", 1);
        break;
    case 'testing':
    case 'production':
        ini_set('display_errors', 0);
        error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
        break;
    case 'installation':
        $url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $c =  (strpos($url, 'install.php') || strpos($url, 'update.php'));
        if ($c == false) {
            header('Location: ' . SITE_URL . '/install.php');
            exit;
        }
        break;
    default:
        header("HTTP/1.1 503 Service Unavailable.", true, 503);
        echo "The application environment is not set correctly";
        exit;
        break;
}
