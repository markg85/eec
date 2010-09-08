<?php
    
    if(!defined("ADMIN_AREA"))
    {
        die("You need to be logged into the admin area to install a module.");
    }
    
    echo 'Attempting to install the fotos module!';
    $core->get("adminhelper")->installModule($core->get("rest_handling")->getModule());
    
    
?>
