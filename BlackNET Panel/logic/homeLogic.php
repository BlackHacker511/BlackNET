<?php

$client = new BlackNET\Clients($database, $utils);
$allClients = $client->getClients();

$countries = $client->getCountries();

$columns = [
    "Victim ID",
    "IP Address",
    "Computer Name",
    "User Status",
    "Country",
    "OS",
    "Installed Date",
    "Version",
    "Status",
];

$disabled = (empty($allClients)) ? "disabled" : null;


$i = 1;

$toggle = [];

foreach ($columns as $column) {
    $item = sprintf(
        '<a href="#" class="toggle-vis" data-column="%d" data-label-text="%s">%s</a>',
        $i,
        $column,
        $column
    );
    array_push($toggle, $item);
    $i++;
}

// Roles Controller
if (isset($_GET['action'])) {
    $action = $utils->sanitize($_GET['action']);

    if (isset($_GET['msg'])) {
        $msg = $utils->sanitize($_GET['msg']);

        if ($action == "modules") {
            if ($msg == "forbidden") {
                $forbidden_message = $utils->dismissibleAlert(
                    "<b>Error!</b> You are trying to access modules it's <b>forbidden</b>.",
                    "danger",
                    "times-circle"
                );
            }
        } elseif ($action == "settings") {
            if ($msg == "forbidden") {
                $forbidden_message = $utils->dismissibleAlert(
                    "<b>Error!</b> You are trying to access network settings it's <b>forbidden</b>.",
                    "danger",
                    "times-circle"
                );
            }
        }
    }
}
$page = "home";
