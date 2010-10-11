<?php

    class EEC_ACL_Role
    {
        private $_sRoleName;
        
        public function __construct($sRoleName)
        {
            $this->_sRoleName = $sRoleName;
        }
        
        public function getRoleName()
        {
            return $this->_sRoleName;
        }
        
    }

?>
