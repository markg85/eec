<?php
    
    class EEC_Cache
    {
        const CT_APC        = 0;
        const CT_FILE       = 1;
        const CT_MEMCACHED  = 2;
        const CT_ARRAY      = 3;
        
        private $_CacheObject = null;
        
        public function __construct($iType = EEC_Cache::CT_APC)
        {
            switch($iType)
            {
                case self::CT_APC:
                    if(extension_loaded('apc'))
                    {
                        require_once EEC_BASE_PATH . 'classes/cache/APC.php';
                        $this->_CacheObject = new EEC_Cache_APC();
                    }
                    else
                    {
                        die('Could not create APC cache object. APC is not available!');
                    }
                    break;
                case self::CT_FILE:
                    require_once EEC_BASE_PATH . 'classes/cache/File.php';
                    $this->_CacheObject = new EEC_Cache_File();
                    break;
                case self::CT_MEMCACHED:
                    if(extension_loaded('memcached'))
                    {
                        require_once EEC_BASE_PATH . 'classes/cache/Memcached.php';
                        $this->_CacheObject = new EEC_Cache_Memcached();
                    }
                    else
                    {
                        die('Could not create APC cache object. APC is not available!');
                    }
                    break;
                case self::CT_ARRAY:
                    require_once EEC_BASE_PATH . 'classes/cache/Array.php';
                    $this->_CacheObject = new EEC_Cache_Array();
                    break;
                default:
                    require_once EEC_BASE_PATH . 'classes/cache/Array.php';
                    $this->_CacheObject = new EEC_Cache_Array();
                    echo 'The provided cache preference is not usable. Using the Array cache (thus no cache at all)!';
            }
        }
        
        public function get($sKey = null)
        {
            if(!is_null($sKey))
            {
                return $this->_CacheObject->get($sKey);
            }
            return false;
            
        }
        
        public function add($sKey = null, $mValue, $iTTL = 0)
        {
            if(!is_null($sKey) && !$this->get($sKey))
            {
                $this->_CacheObject->add($sKey, $mValue, $iTTL);
            }
        }
        
        public function update($sKey = null, $mValue, $iTTL = 0)
        {
            if(!is_null($sKey))
            {
                $this->_CacheObject->update($sKey);
            }
            else
            {
                die('The key: '.$sKey.' cannot be null.');
            }
        }
        
        public function delete($sKey = null)
        {
            if(!is_null($sKey))
            {
                $this->_CacheObject->delete($sKey);
            }
            else
            {
                die('The key: '.$sKey.' cannot be null.');
            }
        }
    }
    
    
?>
