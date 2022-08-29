<?php
if (isset($_COOKIE['darkmode']) && $_COOKIE['darkmode'] != false) :
    echo '<link href="https://stackpath.bootstrapcdn.com/bootswatch/4.5.2/darkly/bootstrap.min.css" rel="stylesheet">';

    $theme_name = "dark";

else :

    $theme_name = "light";

endif;
