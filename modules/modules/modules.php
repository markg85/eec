<?php
    $core = Core::getInstance();

    // Install this module
    if($core->get("rest_handling")->getItem() == "install")
    {
        $core->get("adminhelper")->installModule($core->get("rest_handling")->getFirstAfterModule());
    }
    // Uninstall this module
    elseif($core->get("rest_handling")->getItem() == "uninstall")
    {
        $core->get("adminhelper")->deleteModule($core->get("rest_handling")->getFirstAfterModule());
    }
    
    // Other adjustable module settings
    elseif($core->get("rest_handling")->getItem() == "disable")
    {
        $core->get("adminhelper")->disableModule($core->get("rest_handling")->getFirstAfterModule());
    }
    elseif($core->get("rest_handling")->getItem() == "enable")
    {
        $core->get("adminhelper")->enableModule($core->get("rest_handling")->getFirstAfterModule());
    }
    elseif($core->get("rest_handling")->getItem() == "disablerest")
    {
        $core->get("adminhelper")->disableRest($core->get("rest_handling")->getFirstAfterModule());
    }
    elseif($core->get("rest_handling")->getItem() == "enablerest")
    {
        $core->get("adminhelper")->enableRest($core->get("rest_handling")->getFirstAfterModule());
    }
    
    // The pages this module has
    elseif($core->get("rest_handling")->getItem() == "overview")
    {
        /**
         * Some text like: welcome to the Modules module. The following modules are installed:
         * -- list of installed modules--
         * And the following modules can be installed:
         * -- list of installable modules --
         * TODO : TO BE IMPLEMENTED
         */
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
    elseif($core->get("adminhelper")->moduleInstalled($core->get("rest_handling")->getFirstAfterModule()) && $core->get("rest_handling")->getItem() == "uninstall")
    {
        $core->get("adminhelper")->deleteModule($core->get("rest_handling")->getFirstAfterModule());
    }
    // Install any module -- make sure we this one last since it's a heavy function when there are a lot of modules
    elseif(in_array($core->get("rest_handling")->getFirstAfterModule(), array_keys($core->get("adminhelper")->getModuleList())) && $core->get("rest_handling")->getItem() == "install")
    {
        $core->get("adminhelper")->installModule($core->get("rest_handling")->getFirstAfterModule());
    }
?>