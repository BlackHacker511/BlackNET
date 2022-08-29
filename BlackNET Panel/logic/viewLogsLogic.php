<?php

$clients = new BlackNET\Clients($database, $utils);

$logs = $clients->getLogs();

$disabled = (empty($logs)) ? "disabled" : "";

$page = "view_logs";
