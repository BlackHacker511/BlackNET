<?php
if (isset($_GET['msg'])) {
    if (isset($_GET['status'])) {

        if ($_GET['msg'] == "code") {
            if ($_GET['status'] == "enable") {
                echo $utils->alert("2 Factor Authentication has been enabled", "success", "check-circle");
            } elseif ($_GET['status'] == "disable") {
                echo $utils->alert("2 Factor Authentication has been disabled", "success", "check-circle");
            } elseif ($_GET['status'] == "empty") {
                echo $utils->alert("The Code Field is Empty Please Enter a Valid Code", "danger", "times-circle");
            } elseif ($_GET['status'] == "error") {
                echo $utils->alert("Authentication code is incorrect", "danger", "times-circle");
            } else {
                echo $utils->alert("An unexpected error has occurred", "danger", "times-circle");
            }
        }

        if ($_GET['status'] == "csrf") {
            echo $utils->alert("CSRF token is invalid.", "danger", "times-circle");
        }
    }
}
