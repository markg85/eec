<?php
    
    class TemplateManager_Dwoo
    {
        private $_aDataContainers;
        private $_aTplFiles;
        private $_sUseContainerName;
        private $_Dwoo;
        
        public function init()
        {
            include EEC_BASE_PATH . 'dwoo/dwooAutoload.php';
            $this->_Dwoo = new Dwoo();
        }
        
        public function addDataContainer($sDataContainerName)
        {
            $this->_aDataContainers[$sDataContainerName] = new Dwoo_Data();
        }
        
        public function useDataContainer($sDataContainerName)
        {
            $this->_sUseContainerName = $sDataContainerName;
        }
        
        public function assign($sTplVarName, $var)
        {
            $this->_aDataContainers[$this->_sUseContainerName]->assign($sTplVarName, $var);
        }
        
        public function addTemplateFile($sTplFilename)
        {
            $this->_aTplFiles[$sTplFilename] = new Dwoo_Template_File($sTplFilename);
        }
        
        public function output($sTplFilename, $sDataContainerName)
        {
            $this->_Dwoo->output($this->_aTplFiles[$sTplFilename], $this->_aDataContainers[$sDataContainerName]);
        }
        
    }
    
    
?>