<?php

$upload_required = ['uploadfd', "invokecustom", "update", "torrentf"];

$enc = in_array($_POST['command'], $upload_required) ? 'enctype="multipart/form-data"' : null;
