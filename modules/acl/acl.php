<?php
    
    if(!defined("ADMIN_AREA"))
    {
        die("This module can only be used in the admin area!");
    }
    
    $core = Core::getInstance();

    if($core->get("adminhelper")->moduleInstalled($core->get("rest_handling")->getFirstAfterModule()) && $core->get("rest_handling")->getItem() == "permissions")
    {
        // We try to access the ACL part that can set permissions on a module
        $core->get("template_manager")->assign("aPermissions", $core->get("acl")->getOverview($core->get("rest_handling")->getFirstAfterModule()));
    }
    else
    {
        
        $aSubPath = explode("/", $core->get("rest_handling")->getSubPath());
        
        if(count($aSubPath) == 3 && in_array($aSubPath[2], array("create", "read", "update", "delete")) && in_array($core->get("rest_handling")->getItem(), array("allow", "deny")))
        {
            /**
             If we get in here then the URL is fine for ACL changes..
             $aSubPath[0] == RESOURCE (usually the module name)
             $aSubPath[1] == ROLE (usually the user group)
             $aSubPath[2] == CRUD premission to change
             $core->get("rest_handling")->getItem() == either allow or deny for the given crud permission
            */
            
            if($core->get("rest_handling")->getItem() == "allow")
            {
                $core->get("acl")->grant($aSubPath[1], $aSubPath[0], array($aSubPath[2]));
            }
            else
            {
                $core->get("acl")->revoke($aSubPath[1], $aSubPath[0], array($aSubPath[2]));
            }
        }
        
        //var_dump($core->get("rest_handling"));
        var_dump($core->get("rest_handling")->getFirstAfterModule());
        var_dump($core->get("rest_handling")->getItem());
    }
?>