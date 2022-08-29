<?php

$tpl = new BlackNET\Template("layouts/template");

$utils = new BlackNET\Utils();

$database = new BlackNET\Database();

$user = new BlackNET\User($database);

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = isset($_POST['email']) ? $utils->sanitize($_POST['email']) : null;

    $rp = new BlackNET\ResetPassword($database, $user, $utils, $tpl);

    if (isset($username)) {
        if ($rp->sendMessage($username)) {
            $msg = "Instructions has been send to your email";
        } else {
            $err = "Username does not exist!";
        }
    } else {
        $err = "Please enter a valid email";
    }
}

$page = "forgetPassword";
