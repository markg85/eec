<?php
    global $test;
    
    if($test->getLoadedByModule() == 'rest')
    {
        echo '<br />now this is a special rest code path';
    }
    else
    {
        echo '<br />---- DEFAULT CODE PATH ----';
    }
    
?>
