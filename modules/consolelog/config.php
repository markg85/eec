<?php
    
    require_once EEC_BASE_PATH . "classes/Config_Interface.php";
    
    class consolelog_config implements EEC_Config_Interface
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
            return "ConsoleLog";
        }
        
        public function getModuleRestName()
        {
            return "consolelog";
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
            return $this->getModuleName();
        }
        
        public function menuEntries()
        {
            return null;
        }
        
        public function createTableStatement()
        {
            return null;
        }
    }
?>
