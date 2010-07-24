<?php
    
    class EEC_Cache
    {
        const CT_APC        = 0;
        const CT_FILE       = 1;
        const CT_MEMCACHED  = 2;
        
        private $_CacheObject = null;
        
        public function __construct($iType = EEC_Cache::CT_FILE)
        {
            if(extension_loaded('apc'))
            {
                require_once EEC_BASE_PATH . 'classes/cache/APC.php';
                $this->_CacheObject = new EEC_Cache_APC();
            }
            elseif(extension_loaded('memcached'))
            {
                require_once EEC_BASE_PATH . 'classes/cache/Memcached.php';
                $this->_CacheObject = new EEC_Cache_Memcached();
            }
            else
            {
                require_once EEC_BASE_PATH . 'classes/cache/File.php';
                $this->_CacheObject = new EEC_Cache_File();
            }
        }
        
        public function get($sKey = null)
        {
            if(!is_null($sKey))
            {
                $this->_CacheObject->get($sKey);
            }
            die('The key: '.$sKey.' cannot be null.');
            
        }
        
        public function add($sKey = null, $mValue, $iTTL = 0)
        {
            if(!is_null($sKey))
            {
                $this->_CacheObject->add($sKey, $mValue, $iTTL);
            }
            die('The key: '.$sKey.' cannot be null.');
        }
        
        public function update($sKey = null, $mValue, $iTTL = 0)
        {
            if(!is_null($sKey))
            {
                $this->_CacheObject->update($sKey);
            }
            die('The key: '.$sKey.' cannot be null.');
        }
        
        public function delete($sKey = null)
        {
            if(!is_null($sKey))
            {
                $this->_CacheObject->delete($sKey);
            }
            die('The key: '.$sKey.' cannot be null.');
        }
    }
    
    
?>
