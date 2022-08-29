<?php

$g = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();
if ($data->s2fa == false) {
    if (!isset($_SESSION['secret'])) {
        $_SESSION['secret'] = $g->generateSecret();
    }

    $qrcode = \Sonata\GoogleAuthenticator\GoogleQrUrl::generate(
        $_SESSION['username'],
        $_SESSION['secret'],
        'BlackNET'
    );
}

$page = "update_user";
