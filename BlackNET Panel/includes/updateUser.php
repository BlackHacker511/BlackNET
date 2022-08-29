<?php
require_once '../session.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $msg_code = "";

    if ($auth->checkToken($_POST['csrf'], $_SESSION['csrf']) == false) {
        $msg_code = "csrf";
    } else {
        $user_array = [];
        $current_id = (int) $data->id;
        $id = (int) $_POST['id'];
        if ($id == $current_id) {
            $user_array['username'] = $utils->sanitize($_POST['Username']);
            $user_array['email'] = $utils->sanitize($_POST['Email']);
            $user_array['question'] = $utils->sanitize($_POST['questions']);
            $user_array['answer'] = $utils->sanitize($_POST['answer']);
            $user_array['sqenable'] = isset($_POST['sqenable']) ? 1 : 0;

            if ($_POST['Password'] || $_POST['Password'] != "") {
                $password = $utils->sanitize($_POST['Password']);
                $user_array['password'] = password_hash($password, PASSWORD_BCRYPT);
            }

            if (isset($_POST['darkmode'])) {
                $utils->createCookie("darkmode", true);
            } else {
                $utils->deleteCookie("darkmode");
            }

            if ($user->updateUser($id, $user_array)) {
                $_SESSION['username'] = $user_array['username'];
                $msg_code = "yes";
            } else {
                $msg_code = "error";
            }
        } else {
            $msg_code = "attack";
        }
    }

    $utils->redirect(SITE_URL . "/updateUser.php?msg=" . $msg_code);
}
