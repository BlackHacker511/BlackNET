<?php
// Database Settings
define("DB_HOST", "localhost");
define("DB_USER", "Database Username");
define("DB_PASS", "Database Password");
define("DB_NAME", "Database Name");

// Application Settings
define("ADMIN_EMAIL", "localhost@gmail.com");
define("SITE_URL", "Panel URL"); // Example: http://localhost/blacknet
define("APP_NAME", "BlackNET");
define("APP_DEVELOPER", "Black.Hacker");
define("APP_VERSION", "v3.7.0");
define("APP_PATH", dirname(__FILE__, 2) . DIRECTORY_SEPARATOR); // ( Don't Change );
define("MODULES_PATH", APP_PATH . "modules" . DIRECTORY_SEPARATOR); //( Don't Change);
define("DATA_SPLITTER", "|BN|"); // ( Don't Change );
define("LOGS_PATH", APP_PATH . "php_logs.log"); // ( Don't Change );

// Environment Settings
require_once 'environment.php';

// Autoload Composer
require_once APP_PATH . 'vendor/autoload.php';

// Load BlackNET Modules
require_once 'modules.php';
