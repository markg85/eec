<?php
    $core = Core::getInstance();
    
    // If the module is loaded through the REST module
    if($core->getLoadedByModule() == 'rest')
    {
        echo '<br />now this is a special rest code path';
    }
    else
    {
        if($core->get("rest_handling")->getFirstAfterModule() == "install")
        {
            require_once "install.php";
        }
        elseif($core->get("rest_handling")->getFirstAfterModule() == "remove")
        {
            require_once "remove.php";
        }
        elseif($core->get("rest_handling")->getFirstAfterModule() == "disable")
        {
            $core->get("adminhelper")->disableModule($core->get("rest_handling")->getModule());
        }
        elseif($core->get("rest_handling")->getFirstAfterModule() == "enable")
        {
            $core->get("adminhelper")->enableModule($core->get("rest_handling")->getModule());
        }
        elseif($core->get("rest_handling")->getFirstAfterModule() == "disablerest")
        {
            $core->get("adminhelper")->disableRest($core->get("rest_handling")->getModule());
        }
        elseif($core->get("rest_handling")->getFirstAfterModule() == "enablerest")
        {
            $core->get("adminhelper")->enableRest($core->get("rest_handling")->getModule());
        }
        else
        {
            echo '<br />---- DEFAULT CODE PATH ----';
        }
    }
    
?>
