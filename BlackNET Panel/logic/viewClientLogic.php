<?php

$clientid = $utils->sanitize($_GET['vicid']);

$client = new BlackNET\Clients($database, $utils);

$client_data = $client->getClient($clientid);

$client_data_key = [
    "id" => "ID",
    "vicid" => "Victim ID",
    "hwid" => "HWID",
    "ipaddress" => "IP Address",
    "computername" => "Computer Name",
    "country" => "Country",
    "os" => "OS",
    "cpu" => "CPU",
    "gpu" => "GPU",
    "ramsize" => "RAM Size",
    "insdate" => "Installed Date",
    "update_at" => "Update Date",
    "pings" => "Number of Pings",
    "antivirus" => "Antivirus",
    "version" => "Version",
    "status" => "Status",
    "is_usb" => "Is USB",
    "is_admin" => "User Status"
];
