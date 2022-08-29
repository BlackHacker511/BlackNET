<?php

/** BlackNET Modules Loader */

$modules = [];
if (defined("MODULES_PATH")) {
    if (is_dir(MODULES_PATH)) {
        $modules_dir_content = scandir(MODULES_PATH);

        foreach ($modules_dir_content as $m) {
            if (!(in_array($m, ['.', '..', 'index.html']))) {
                array_push($modules, $m);
            }
        }
    }
}
