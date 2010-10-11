<?php
    
    if(!defined("ADMIN_AREA"))
    {
        die("This module can only be used in the admin area!");
    }
    
    $core = Core::getInstance();
    
    // Install this module
    if($core->get("rest_handling")->getItem() == "install")
    {
        $core->getModuleData("modules")->installModule($core->get("rest_handling")->getFirstAfterModule());
    }
    // Uninstall this module
    elseif($core->get("rest_handling")->getItem() == "uninstall")
    {
        $core->getModuleData("modules")->deleteModule($core->get("rest_handling")->getFirstAfterModule());
    }
    
    // Other adjustable module settings
    elseif($core->get("rest_handling")->getItem() == "disable")
    {
        $core->getModuleData("modules")->disableModule($core->get("rest_handling")->getFirstAfterModule());
    }
    elseif($core->get("rest_handling")->getItem() == "enable")
    {
        $core->getModuleData("modules")->enableModule($core->get("rest_handling")->getFirstAfterModule());
    }
    elseif($core->get("rest_handling")->getItem() == "disablerest")
    {
        $core->getModuleData("modules")->disableRest($core->get("rest_handling")->getFirstAfterModule());
    }
    elseif($core->get("rest_handling")->getItem() == "enablerest")
    {
        $core->getModuleData("modules")->enableRest($core->get("rest_handling")->getFirstAfterModule());
    }
    
    // The pages this module has
    elseif($core->get("rest_handling")->getSubPath() == "" && ($core->get("rest_handling")->getItem() == "overview" || $core->get("rest_handling")->getItem() == ""))
    {
        $core->get("template_manager")->assign("aInstalledModules", $core->getModuleData("modules")->getInstalledModules());
        $core->get("template_manager")->assign("aInstallableModules", $core->getModuleData("modules")->getInstallableModules());
    }
    elseif($core->get("rest_handling")->getItem() == "installed_modules")
    {
        /**
         * Same as in overview but without the installable modules.
         */
    }
    elseif($core->get("rest_handling")->getItem() == "available_modules")
    {
        /**
         * Same as in overview but without the installed modules.
         */
    }
    elseif($core->get("rest_handling")->getItem() == "module_details")
    {
        /**
         * How, i don't know yet, but this should be a conditional page that becomes visible when a module is clicked in either the overview or
         * the available_modules page. 
         */
    }
    
    
    // Uninstall any module
    elseif($core->getModuleData("modules")->moduleInstalled($core->get("rest_handling")->getFirstAfterModule()) && $core->get("rest_handling")->getItem() == "uninstall")
    {
        $core->getModuleData("modules")->deleteModule($core->get("rest_handling")->getFirstAfterModule());
    }
    // Install any module -- make sure we this one last since it's a heavy function when there are a lot of modules
    elseif($core->getModuleData("modules")->moduleInstalled($core->get("rest_handling")->getFirstAfterModule()) && $core->get("rest_handling")->getItem() == "install")
    {
        $core->getModuleData("modules")->installModule($core->get("rest_handling")->getFirstAfterModule());
    }
?>