<?php
    
    require_once EEC_BASE_PATH . "classes/Config_Interface.php";
    
    class modules_config implements EEC_Config_Interface
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
            return "Modules";
        }
        
        public function getModuleRestName()
        {
            return "modules";
        }
        
        public function setEnabled($bEnabled = true)
        {
            $this->_bEnabled = $bEnabled;
        }

        public function getEnabled()
        {
            return $this->_bEnabled;
        }
        
        public function setRestEnabled($bEnabled = false)
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
            return "Modules";
        }
        
        public function menuEntries()
        {
            return array("Overview" => "overview",
                         "Installed Modules" => "installed_modules",
                         "Available Modules" => "available_modules",
                         "Module Details" => array("module_details", "Settings" => "settings", "Permissions" => "permissions", "Uninstall" => "uninstall"),
                  );
        }
        
        public function createTableStatement()
        {
            return "-- --------------------------------------------------------

                    --
                    -- Table structure for table `modules`
                    --

                    CREATE TABLE IF NOT EXISTS `modules` (
                    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                    `modulerestname` varchar(25) NOT NULL,
                    `modulename` varchar(50) NOT NULL,
                    `author` varchar(50) NOT NULL,
                    `email` varchar(50) NOT NULL,
                    `version` double unsigned NOT NULL,
                    `enabled` tinyint(1) unsigned NOT NULL,
                    `restenabled` tinyint(1) unsigned NOT NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `modulerestname` (`modulerestname`)
                    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
        }
    }
?>