<?php

$tpl = new BlackNET\Template("layouts/template");
$utils = new BlackNET\Utils();
$database = new BlackNET\Database();
$user = new BlackNET\User($database);
$page = "resetPasswordPage";
$token = $utils->sanitize($_GET['token']);

$updatePassword = new BlackNET\ResetPassword($database, $user, $utils, $tpl);

if ($updatePassword->isExist($token) == true) {
    $data = $updatePassword->getUserAssignToToken($token);
    $question = $user->isQuestionEnabled($data->username);
    $answered = isset($_GET['answered']) ? $utils->sanitize($_GET['answered']) : "false";
    if ($question != false) {
        if ($answered != "true") {
            $utils->redirect(SITE_URL . "/question.php?username=$data->username&token=$token");
        }
    }
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $Password = $utils->sanitize($_POST['password']);
        $confirmPassword = $utils->sanitize($_POST['confirmPassword']);
        if ($Password == $confirmPassword) {
            if ($updatePassword->updatePassword($token, $data->username, $_POST['password'])) {
                $msg = "Password has been updated";
            } else {
                $err = "Please enter more then 8 characters";
            }
        } else {
            $err = "Password confirm is incorrect";
        }
    }
} else {
    $utils->redirect(SITE_URL . "/expire.php");
}

session_destroy();
