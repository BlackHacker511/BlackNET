<?php
require_once dirname(__DIR__) . '/session.php';
require_once APP_PATH . 'classes/Clients.php';

if ($auth->checkToken($_POST['csrf'], $_SESSION['csrf']) == false) {
    $utils->redirect(SITE_URL . "/viewlogs.php?msg=csrf");
} else {
    $client = new BlackNET\Clients($database, $utils);
    if (isset($_POST['log'])) {
        foreach ($_POST['log'] as $logs) {
            $client->deleteLog((int) $logs);
        }
    }

    $utils->redirect(SITE_URL . "/viewlogs.php?msg=yes");
}
