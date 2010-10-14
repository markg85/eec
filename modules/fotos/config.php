<?php
    
    require_once EEC_BASE_PATH . "classes/Config_Interface.php";
    
    class fotos_config implements EEC_Config_Interface
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
            return "fotos";
        }
        
        public function getModuleRestName()
        {
            return "fotos";
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
            return "Fotos";
        }
        
        public function menuEntries()
        {
            return array("Overview" => "overview",
                  "Albums" => array("albums", "Overview" => "overview", "Add" => "add", "Edit" => "edit", "Delete" => "delete"),
                  "Add" => "add",
                  "Edit" => "edit",
                  "Delete" => "delete",
                  );
        }

        public function createTableStatement()
        {
            return null;
        }
    }

?>
