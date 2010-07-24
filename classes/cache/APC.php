<?php
    
    class EEC_Cache_APC
    {
        public function get($sKey)
        {
            apc_fetch($sKey);
        }
        
        public function add($sKey, $mValue, $iTTL)
        {
            apc_add($sKey, $mValue, $iTTL);
        }
        
        public function update($sKey, $mValue, $iTTL)
        {
            apc_add($sKey, $mValue, $iTTL);
        }
        
        public function delete($sKey)
        {
            apc_delete($sKey);
        }
        
    }
    
?>
 
