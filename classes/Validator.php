<?php
    
    class Validator
    {
        private $aCustomFilters;
        
        public function callCustomFilter($mInput, $sFilter)
        {
            if(isset($this->aCustomFilters[$sFilter]))
            {
                return strlen($mInput) == 0 || filter_var($mInput, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => $this->aCustomFilters[$sFilter])));
            }
            return null;
        }
        
        public function setCustomFilter($sName, $sExpression)
        {
            $this->aCustomFilters[$sName] = $sExpression;
        }
        
        ///
        /// Start predefined filters
        ///
        
        /**
         * isString validates a string with 1 or more characters
         */
        public function isString($mInput)
        {
            return strlen($mInput) > 0 && filter_var($mInput, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[^\x-\x1F]+$/")));
        }

        /**
         * _isString validates a string with 0 or more characters
         */
        public function _isString($mInput)
        {
            return strlen($mInput) == 0 || filter_var($mInput, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[^\x-\x1F]+$/")));
        }

    }
    
?>
