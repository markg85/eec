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
        else
        {
            echo '<br />---- DEFAULT CODE PATH ----';
        }
    }
    
?>
