<?php
    
    class EEC_Config
    {
        private $_oCacheObject;
        
        public function __construct()
        {
            $this->_oCacheObject = new EEC_Cache(EEC_Cache::CT_APC);
        }
        
        public function get($sKey = null)
        {
            return $this->_oCacheObject->get($sKey);
        }
        
        public function add($sKey = null, $mValue)
        {
            $this->_oCacheObject->add($sKey, $mValue);
        }
        
        public function update($sKey = null, $mValue)
        {
            $this->_oCacheObject->update($sKey, $mValue);
        }
        
        public function delete($sKey = null)
        {
            $this->_oCacheObject->delete($sKey);
        }
    }
    
    
?>
