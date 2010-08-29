<?php
    
    class EEC_Cache_Array
    {
        private $_aCache;
        
        public function get($sKey)
        {
            if(isset($this->_aCache[$sKey]))
            {
                return unserialize($this->_aCache[$sKey]);
            }
        }
        
        public function add($sKey, $mValue, $iTTL)
        {
            if(!isset($this->_aCache[$sKey]))
            {
                $this->_aCache[$sKey] = serialize($mValue);
            }
            else
            {
                die('Key: ' . $sKey . ' already exists!');
            }
        }
        
        public function update($sKey, $mValue, $iTTL)
        {
            $this->_aCache[$sKey] = serialize($mValue);
        }
        
        public function delete($sKey)
        {
            unset($this->_aCache[$sKey]);
        }
        
    }
    
?>
 
