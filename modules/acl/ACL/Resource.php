<?php

    class EEC_ACL_Resource
    {
        private $_sResourceName;
        
        public function __construct($sResourceName)
        {
            $this->_sResourceName = $sResourceName;
        }
        
        public function getResourceName()
        {
            return $this->_sResourceName;
        }
        
    }

?>
