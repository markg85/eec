<?php
    
    require_once EEC_BASE_PATH . "classes/Data_Interface.php";

    class template_data implements EEC_Data_Interface
    {
        private $_sTemplateStorage = false;
        private $_oDatabase;
        
        public function __construct()
        {
            $this->_oDatabase = EEC_Database::getInstance();
            
            // Set a default path for template storage
            $this->setTemplateStorageFolder(EEC_BASE_PATH . 'modules/template/stored_template_files/');
        }
        
        public function setTemplateStorageFolder($sTemplateStorage)
        {
            $checkedPath = realpath($sTemplateStorage);
            if($checkedPath !== false)
            {
                $this->_sTemplateStorage = $checkedPath . '/';
            }
            else
            {
                die('The set path: ' . $sTemplateStorage . ' is not a valid path on this system! Please provide a valid path.');
            }
        }
        
        public function getTemplateStorageFolder()
        {
            return $this->_sTemplateStorage;
        }
        
        public function addTemplate($sTemplateName, $sVar1, $sVar2, $sVar3, $sVar4, $sVar5, $sTemplateData, $update = false)
        {
            // First we do some safety checks for a template that gets inserted in the database and in the file.
            if(empty($sTemplateData))
            {
                die('You need to fill in template data!');
            }
            
            if($this->_sTemplateStorage === false)
            {
                die('You need to provide a valid path for template storage first! That folder also has to have 777 permissions!');
            }
            
            if(!is_writable($this->_sTemplateStorage))
            {
                die('You\'re template storing folder: ' . $this->_sTemplateStorage . ' is not writable! Please give it 777 permissions!');
            }
            
            if(file_exists($this->_sTemplateStorage . $sTemplateName) && !$update)
            {
                die('You\'re trying to overwrite an existing template in the add command. Use edit instead!');
            }
            
            $result = $this->_oDatabase->query("SELECT template_name FROM template WHERE template_name = '".$sTemplateName."';");
            $aData = $result->fetch_all(MYSQLI_ASSOC);
            
            if(!empty($aData) && !$update)
            {
                die('You\'re trying to add a template record to the database while there is one already with the same name! Be aware that template names have to be unique!');
            }
            
            // Store the template data in a file which will be used by the template manager.
            if(file_put_contents($this->_sTemplateStorage . $sTemplateName, $sTemplateData) === false)
            {
                die('Failed to store the template data in a file.');
            }
            
            if($update)
            {
                $sQuery = sprintf("UPDATE `template` SET  
                                    `template_name` =  '%s',
                                    `template_var_1` =  '%s',
                                    `template_var_2` =  '%s',
                                    `template_var_3` =  '%s',
                                    `template_var_4` =  '%s',
                                    `template_var_5` =  '%s' WHERE `template_name` = %s;", $sTemplateName, $sVar1, $sVar2, $sVar3, $sVar4, $sVar5, $sTemplateName);
            }
            else
            {
                $sQuery = sprintf("INSERT INTO `template` (
                        `id` ,
                        `template_name` ,
                        `template_var_1` ,
                        `template_var_2` ,
                        `template_var_3` ,
                        `template_var_4` ,
                        `template_var_5`
                        )
                        VALUES (
                        NULL ,  '%s',  '%s',  '%s',  '%s',  '%s',  '%s'
                        );", $sTemplateName, $sVar1, $sVar2, $sVar3, $sVar4, $sVar5);
            }
            
            // Put template metadata (which can be used by other modules) in the database. 
            $this->_oDatabase->query($sQuery);
            
            file_put_contents($this->_sTemplateStorage . $sTemplateName, $sTemplateData);
        }
        
        public function updateTemplate($sTemplateName, $sVar1, $sVar2, $sVar3, $sVar4, $sVar5, $sTemplateData)
        {
            $this->addTemplate($sTemplateName, $sVar1, $sVar2, $sVar3, $sVar4, $sVar5, $sTemplateData, true);
        }
        
        public function getOverview()
        {
            $result = $this->_oDatabase->query("SELECT * FROM template;");
            
            if($result !== false)
            {
                $aData = $result->fetch_all(MYSQLI_ASSOC);
                return $aData;
            }
        }
        
        public function getAllTemplateData($id)
        {
            $result = $this->_oDatabase->query("SELECT * FROM template WHERE id = ".$id.";");
            
            if($result !== false)
            {
                $aData = $result->fetch_all(MYSQLI_ASSOC);
                
                $aData[0]['raw_data'] = file_get_contents($this->_sTemplateStorage . $aData[0]['template_name']);
                return $aData;
            }
        }
        
        public function deleteTemplate($id)
        {
            $result = $this->_oDatabase->query("SELECT * FROM template WHERE id = ".$id.";");
            
            if($result !== false)
            {
                $aData = $result->fetch_all(MYSQLI_ASSOC);
                $sQuery = "DELETE FROM `template` WHERE `id` = '".$id."';";
                $this->_oDatabase->query($sQuery);
                unlink($this->_sTemplateStorage . $aData[0]['template_name']);
            }
            else
            {
                die('The file you want to delete is not known in the database!');
            }
        }
    }
?> 
