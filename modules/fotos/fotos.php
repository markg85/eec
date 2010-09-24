<?php
    $core = Core::getInstance();
    
    // If the module is loaded through the REST module
    if($core->getLoadedByModule() == 'rest')
    {
        echo '<br />now this is a special rest code path';
    }
    else
    {
        echo '<br />---- DEFAULT CODE PATH ----';
    }
    
?>
