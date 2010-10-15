<?php
    
    // overview
    $core = Core::getInstance();
    
    if($core->get("rest_handling")->getSubPath() == "" && ($core->get("rest_handling")->getItem() == "overview" || $core->get("rest_handling")->getItem() == ""))
    {
        $core->get("template_manager")->assign("aMenuItems", $core->getModuleData("modules")->getAdminMenu("template"));
        $core->get("template_manager")->assign("aTemplateItems", $core->getModuleData('template')->getOverview());
    }
    elseif($core->get("rest_handling")->getItem() == "add")
    {
        if($core->get("rest_handling")->isPost())
        {
            $core->getModuleData('template')->addTemplate($_POST['template_name'], $_POST['var_1'], $_POST['var_2'], $_POST['var_3'], $_POST['var_4'], $_POST['var_1'], $_POST['template_data']);
        }
    }
    elseif($core->get("rest_handling")->getSubPath() == "edit" && ctype_digit($core->get("rest_handling")->getItem()))
    {
        if($core->get("rest_handling")->isPost())
        {
            $core->getModuleData('template')->updateTemplate($_POST['template_name'], $_POST['var_1'], $_POST['var_2'], $_POST['var_3'], $_POST['var_4'], $_POST['var_1'], $_POST['template_data']);
        }
        $core->get("template_manager")->assign("aTemplateMetadata", $core->getModuleData('template')->getAllTemplateData($core->get("rest_handling")->getItem()));
    }
    elseif($core->get("rest_handling")->getSubPath() == "delete" && ctype_digit($core->get("rest_handling")->getItem()))
    {
        $core->getModuleData('template')->deleteTemplate($core->get("rest_handling")->getItem());
    }
    
?>