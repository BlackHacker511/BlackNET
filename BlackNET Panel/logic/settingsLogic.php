<?php

$smtp = new BlackNET\Mailer($database);

$settings = new BlackNET\Settings($database);

$smtp_types = ["None", "SSL", "TLS"];

$page = "network_settings";
