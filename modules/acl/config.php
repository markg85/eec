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

    }
?>
