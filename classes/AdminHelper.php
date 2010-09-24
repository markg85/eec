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
            //$result = $this->_oDatabase->query("SELECT modulerestname FROM modules;");
            //$aData = $result->fetch_array(MYSQLI_ASSOC);
            //return $aData;
            
            
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
        
        public function getModuleInfo($sModule)
        {
            $result = $this->_oDatabase->query("SELECT * FROM modules WHERE modulerestname = '".$sModule."';");
            $aData = $result->fetch_array(MYSQLI_ASSOC);
            return $aData;
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
            
            // put the menu structure in the database
            $sQeuryTemplateMenu = "INSERT INTO `admin_menu` (
                                  `module_id` ,
                                  `name` ,
                                  `url` ,
                                  `desc` ,
                                  `parent_id`
                                  )
                                  VALUES (
                                  '%s' ,  '%s',  '%s',  '%s',  '%s'
                                  );";
            $iModuleId = $this->_oDatabase->insert_id;
            
            foreach($oModuleObject->menuEntries() as $sKey => $mValue)
            {
                
                if(is_array($mValue))
                {
                    $this->_oDatabase->query(sprintf($sQeuryTemplateMenu, $iModuleId, $sKey, current($mValue), "", 0));
                    $iLastId = $this->_oDatabase->insert_id;
                    
                    foreach($mValue as $sSubKey => $sSubValue)
                    {
                        if(is_string($sSubKey))
                        {
                            $this->_oDatabase->query(sprintf($sQeuryTemplateMenu, $iModuleId, $sSubKey, $sSubValue, "", $iLastId));
                        }
                    }
                }
                else
                {
                    $this->_oDatabase->query(sprintf($sQeuryTemplateMenu, $iModuleId, $sKey, $mValue, "", 0));
                    
                }
            }
            
        }
        
        public function deleteModule($sModule)
        {
            $aModuleData = $this->getModuleInfo($sModule);
            
            if(is_null($aModuleData))
            {
                // no module data available...
            }
            else
            {
                $sQuery = "DELETE FROM `modules` WHERE `modulerestname` = '".$sModule."';";
                $this->_oDatabase->query($sQuery);
                
                $sQuery = "DELETE FROM `admin_menu` WHERE `module_id` = '".$aModuleData['id']."';";
                $this->_oDatabase->query($sQuery);
            }
        }
        
        public function disableModule($sModule)
        {
            $sQuery = "UPDATE `modules` SET `enabled` = '0' WHERE `modulerestname` = '".$sModule."';";
            $this->_oDatabase->query($sQuery);
        }
        
        public function disableRest($sModule)
        {
            $sQuery = "UPDATE `modules` SET `restenabled` = '0' WHERE `modulerestname` = '".$sModule."';";
            $this->_oDatabase->query($sQuery);
        }
        
        public function enableModule($sModule)
        {
            $sQuery = "UPDATE `modules` SET `enabled` = '1' WHERE `modulerestname` = '".$sModule."';";
            $this->_oDatabase->query($sQuery);
        }
        
        public function enableRest($sModule)
        {
            $sQuery = "UPDATE `modules` SET `restenabled` = '1' WHERE `modulerestname` = '".$sModule."';";
            $this->_oDatabase->query($sQuery);
        }
        
        public function moduleInstalled($sModule)
        {
            $result = $this->_oDatabase->query("SELECT modulerestname FROM modules WHERE modulerestname = '".$sModule."';");
            $aData = $result->fetch_all(MYSQLI_ASSOC);
            
            if(!empty($aData))
            {
                return true;
            }
            return false;
        }
    }
    
?>
