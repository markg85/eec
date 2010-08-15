<?php
    $core = Core::getInstance();
    
    if($core->getLoadedByModule() == 'rest')
    {
        echo '<br />now this is a special rest code path';
    }
    else
    {
        echo '<br />---- DEFAULT CODE PATH ----';
    }
    
    $core->get('config')->add('test', 'some config test value....');
    
    var_dump($core->get('config')->get('test'));
    
?>
