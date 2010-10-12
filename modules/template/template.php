<?php
    
    // overview
    $core = Core::getInstance();
    
    if($core->get("rest_handling")->getSubPath() == "" && ($core->get("rest_handling")->getItem() == "overview" || $core->get("rest_handling")->getItem() == ""))
    {
        $core->get("template_manager")->assign("aMenuItems", $core->getModuleData("modules")->getAdminMenu("template"));
    }
    elseif($core->get("rest_handling")->getItem() == "add")
    {
        
    }
    
?>