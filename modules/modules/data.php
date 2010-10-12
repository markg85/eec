<?php
    
    require_once EEC_BASE_PATH . "classes/Data_Interface.php";
    
    class modules_data implements EEC_Data_Interface
    {
        private $_oDatabase;
        private $_oLog;
        private $_aModuleList;
        
        public function __construct()
        {
            //$this->_eecObject = Core::getInstance();
            $this->_oDatabase = EEC_Database::getInstance();
            $this->_oLog = Core::getInstance()->getModuleData('consolelog');
        }
        
        /**
         * Returns all modules from the modules dir.
         */
        public function getAllModulesFromModuleDir()
        {
            $this->_aModuleList = array();
            $aFiles = scandir(EEC_MODULE_PATH);
            
            foreach($aFiles as $file)
            {
                if(is_dir(EEC_MODULE_PATH . $file) && $file != ".." && $file != ".")
                {
                    $sConfigPath    = EEC_MODULE_PATH . $file . "/config.php";
                    $sDataPath      = EEC_MODULE_PATH . $file . "/data.php";
                    $sAdminTplPath  = EEC_MODULE_PATH . $file . "/admin.tpl";
                    $sMainTplPath   = EEC_MODULE_PATH . $file . "/main.tpl";
                    $bTrue = true;
                    
                    if(!file_exists($sAdminTplPath))
                    {
                        $this->_oLog->log($file, 1, 'NO_ADMIN_TEMPLATE_FILE');
                        $bTrue = false;
                    }
                    
                    if(!file_exists($sMainTplPath))
                    {
                        $this->_oLog->log($file, 1, 'NO_MAIN_TEMPLATE_FILE');
                        $bTrue = false;
                    }
                    
                    if(file_exists($sConfigPath))
                    {
                        require_once $sConfigPath;
                        $sConfigObjectName = $file . "_config";
                        $oConfigObject = new $sConfigObjectName();
                        
                        if($oConfigObject instanceof EEC_Config_Interface && $bTrue)
                        {
                            $this->_aModuleList[$file]['module'] = $file;
                            $this->_aModuleList[$file]['configpath'] = $sConfigPath;
                            $this->_aModuleList[$file]['configobject'] = $oConfigObject;
                        }
                        else
                        {
                            $this->_oLog->log($file, 1, 'NOT_IMPLEMENTING_CONFIG_INTERFACE');
                        }
                    }
                    else
                    {
                        $this->_oLog->log($file, 1, 'NO_CONFIG_OBJECT');
                    }
                    
                    if(file_exists($sDataPath))
                    {
                        require_once $sDataPath;
                        $sDataObjectName = $file . "_data";
                        $oDataObject = new $sDataObjectName();
                        
                        if($oDataObject instanceof EEC_Data_Interface)
                        {
                            // We don't need to do anything with it! All we need to know is if it exists and implements what we require.
                        }
                        else
                        {
                            $this->_oLog->log($file, 1, 'NOT_IMPLEMENTING_DATA_INTERFACE');
                        }
                    }
                    else
                    {
                        $this->_oLog->log($file, 1, 'NO_DATA_OBJECT');
                    }
                }
            }
            return $this->_aModuleList;
        }
        
        /**
         * Returns all modules that are in the modules dir, but not installed.
         */
        public function getInstallableModules()
        {
            $result = $this->_oDatabase->query("SELECT modulerestname FROM modules;");
            $aData = $result->fetch_all(MYSQLI_ASSOC);
            
            // hmm.. not exactly the kind of array i can use.. Make it usable.
            $aInstalledModules = array();
            if(!empty($aData))
            {
                foreach($aData as $data)
                {
                    $aInstalledModules[] = current($data);
                }
            }
            
            // I only need simple data.. The other data is interesting but not needed here.
            $aModulesFromDir = $this->getAllModulesFromModuleDir();
            $aSimpleModulesFromDir = array_keys($aModulesFromDir);
            
            $aInstallableModules = array();
            
            foreach($aSimpleModulesFromDir as $sModule)
            {
                if(!in_array($sModule, $aInstalledModules))
                {
                    $aInstallableModules[$sModule] = $aModulesFromDir[$sModule];
                }
            }
            
            return $aInstallableModules;
        }
        
        /**
         * Returns all installed modules
         */
        public function getInstalledModules()
        {
            $result = $this->_oDatabase->query("SELECT * FROM modules;");
            $aData = $result->fetch_all(MYSQLI_ASSOC);
            return $aData;
        }
        
        public function getModuleInfo($sModule)
        {
            $result = $this->_oDatabase->query("SELECT * FROM modules WHERE modulerestname = '".$sModule."';");
            $aData = $result->fetch_array(MYSQLI_ASSOC);
            return $aData;
        }
        
        public function installModule($sModule)
        {
            if(is_null($this->_aModuleList))
            {
                $this->getAllModulesFromModuleDir();
            }
            
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
                        '".$oModuleObject->getModuleRestName()."',  '".$oModuleObject->getModuleName()."',  '".$oModuleObject->getAuthor()."',  '".$oModuleObject->getEmail()."',  '".$oModuleObject->getVersion()."',  '".$oModuleObject->getEnabled()."',  '".$oModuleObject->getRestEnabled()."');";
            
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
            
            if(is_array($oModuleObject->menuEntries()))
            {
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
            
            // Add a default resource with the modulerestname.
            Core::getInstance()->getModuleData("acl")->addResource(new EEC_ACL_Resource($oModuleObject->getModuleRestName()));
            
            // Give the admin all permissions on this module
            Core::getInstance()->getModuleData("acl")->grant('admin', $oModuleObject->getModuleRestName(), array('crud'));
            
            // Log line
            $this->_oLog->log($sModule, 3, 'Inserted module data into the database.');
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
            
            Core::getInstance()->getModuleData("acl")->dropResource($sModule);
            
            $this->_oLog->log($sModule, 3, 'Module data deleted from database.');
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
        
        public function getAdminMenu($sModule)
        {
            $result = $this->_oDatabase->query("SELECT * FROM admin_menu WHERE module_id IN (SELECT id FROM modules WHERE modulerestname = 'template');");
            $aData = $result->fetch_all(MYSQLI_ASSOC);
            
            if(!empty($aData))
            {
                return $aData;
            }
            return false;
        }
    }
?> 
