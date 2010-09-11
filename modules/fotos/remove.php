<?php
    
    if(!defined("ADMIN_AREA"))
    {
        die("You need to be logged into the admin area to install a module.");
    }
    
    echo 'Attempting to remove the fotos module!';
    $core->get("adminhelper")->deleteModule($core->get("rest_handling")->getModule());
    
    
?>
