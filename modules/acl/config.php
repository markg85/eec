<?php
    
    require_once EEC_BASE_PATH . "classes/Config_Interface.php";
    
    class acl_config implements EEC_Config_Interface
    {
        private $_bEnabled      = true;
        private $_bRestEnabled  = true;
        
        public function getAuthor()
        {
            return "Mark G.";
        }
        
        public function getEmail()
        {
            return "markg85@gmail.com";
        }
        
        public function getVersion()
        {
            return 0.1;
        }
        
        public function getModuleName()
        {
            return "acl";
        }
        
        public function getModuleRestName()
        {
            return "acl";
        }
        
        public function setEnabled($bEnabled = true)
        {
            $this->_bEnabled = $bEnabled;
        }

        public function getEnabled()
        {
            return $this->_bEnabled;
        }
        
        public function setRestEnabled($bEnabled = true)
        {
            $this->_bRestEnabled = $bEnabled;
        }
        
        public function getRestEnabled()
        {
            return $this->_bRestEnabled;
        }
        
        // Menu configuration
        public function menuName()
        {
            return "ACL";
        }
        
        public function menuEntries()
        {
            return array("Overview" => "overview",
                  "Roles" => array("roles", "Overview" => "overview", "Add" => "add", "Edit" => "edit", "Delete" => "delete"),
                  "Resources" => array("resources", "Overview" => "overview", "Add" => "add", "Edit" => "edit", "Delete" => "delete"),
                  "Permissions" => array("permissions", "Overview" => "overview", "Add" => "add", "Edit" => "edit", "Delete" => "delete"),
                  );
        }

        public function createTableStatement()
        {
            return "-- --------------------------------------------------------

                    --
                    -- Table structure for table `acl`
                    --

                    CREATE TABLE IF NOT EXISTS `acl` (
                    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                    PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ACL Table' AUTO_INCREMENT=1 ;

                    -- --------------------------------------------------------

                    --
                    -- Table structure for table `acl_permissions`
                    --

                    CREATE TABLE IF NOT EXISTS `acl_permissions` (
                    `role_id` int(10) unsigned NOT NULL,
                    `resource_id` int(10) unsigned NOT NULL,
                    `create` tinyint(1) NOT NULL DEFAULT '0',
                    `read` tinyint(1) NOT NULL DEFAULT '0',
                    `update` tinyint(1) NOT NULL DEFAULT '0',
                    `delete` tinyint(1) NOT NULL DEFAULT '0',
                    PRIMARY KEY (`role_id`,`resource_id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='All permissions are in this table with foreign keys to resou';

                    -- --------------------------------------------------------

                    --
                    -- Table structure for table `acl_resources`
                    --

                    CREATE TABLE IF NOT EXISTS `acl_resources` (
                    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                    `resource` varchar(100) NOT NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `resource` (`resource`)
                    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='ACL Resources (the things where you can set permissions on)' AUTO_INCREMENT=1 ;

                    -- --------------------------------------------------------

                    --
                    -- Table structure for table `acl_roles`
                    --

                    CREATE TABLE IF NOT EXISTS `acl_roles` (
                    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                    `role` varchar(50) NOT NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `role` (`role`)
                    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='ACL Roles (thus user groups)' AUTO_INCREMENT=1 ;";
        }
    }
?>
