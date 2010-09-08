<?php
    
    class EEC_AdminHelper
    {
        const MODULES_ACTIVE        = 0;
        const MODULES_INACTIVE      = 1;
        const MODULES_ALL           = 2;
        
        private $_oDatabase;
        private $_aModuleList;
        
        public function __construct()
        {
            //$this->_eecObject = Core::getInstance();
            $this->_oDatabase = EEC_Database::getInstance();
        }
        
        // Only modules that fit the demands below will be added in the module list and then showed in the admin panel.
        public function getModuleList($iType = self::MODULES_ALL)
        {
            $this->_aModuleList = array();
            $aFiles = scandir(EEC_MODULE_PATH);
            
            foreach($aFiles as $file)
            {
                if(is_dir(EEC_MODULE_PATH . $file) && $file != ".." && $file != ".")
                {
                    $sConfigPath = EEC_MODULE_PATH . $file . "/config.php";
                    
                    if(file_exists($sConfigPath))
                    {
                        require_once $sConfigPath;
                        $sConfigObjectName = $file . "_config";
                        $oConfigObject = new $sConfigObjectName();
                        
                        if($oConfigObject instanceof EEC_Config_Interface)
                        {
                            $this->_aModuleList[$file]['module'] = $file;
                            $this->_aModuleList[$file]['configpath'] = $sConfigPath;
                            $this->_aModuleList[$file]['configobject'] = $oConfigObject;
                        }
                        else
                        {
                            var_dump("MODULE :: " . $file . " - NOT_IMPLEMENTING_INTERFACE");
                        }
                    }
                    else
                    {
                        var_dump("MODULE :: " . $file . " - NO_CONFIG_OBJECT");
                    }
                }
            }
            return $this->_aModuleList;
        }
        
        public function installModule($sModule)
        {
            $result = $this->_oDatabase->query("SELECT modulerestname FROM modules WHERE modulerestname = '".$sModule."';");
            $aData = $result->fetch_all(MYSQLI_ASSOC);
            
            if(!empty($aData))
            {
                die("The module: " . $sModule . " is already installed!");
            }
            
            $oModuleObject = $this->_aModuleList[$sModule]['configobject'];
            
            $sQuery = " INSERT INTO `modules` (
                        `modulerestname` ,
                        `modulename` ,
                        `author` ,
                        `email` ,
                        `version` ,
                        `enabled` ,
                        `restenabled`
                        )
                        VALUES (
                        '".$oModuleObject->getModuleName()."',  '".$oModuleObject->getModuleRestName()."',  '".$oModuleObject->getAuthor()."',  '".$oModuleObject->getEmail()."',  '".$oModuleObject->getVersion()."',  '".$oModuleObject->getEnabled()."',  '".$oModuleObject->getRestEnabled()."');";
            
            $this->_oDatabase->query($sQuery);
            // Run a query to put all the data from the oConfigObject in the database table: "modules"
            // also put all the modules menu entries in a table (yet to determine it's structure)
        }
        
        public function deleteModule($sModule)
        {
            $sQuery = "DELETE FROM `modules` WHERE `modulerestname` = '".$sModule."';";
            $this->_oDatabase->query($sQuery);
        }
    }
    
?>
