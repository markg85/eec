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
    }
?>