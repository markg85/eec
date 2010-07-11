<?php
    
    class Validator
    {
        private $aCustomFilters;
        
        public function callCustomFilter($mInput, $sFilter)
        {
            if(isset($this->aCustomFilters[$sFilter]))
            {
                return filter_var($mInput, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => $this->aCustomFilters[$sFilter])));
            }
            return null;
        }
        
        public function setCustomFilter($sName, $sExpression)
        {
            $this->aCustomFilters[$sName] = $sExpression;
        }
        
        public function isString($mInput)
        {
            return filter_var($mInput, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[^\x-\x1F]+$/")));
        }
        
    }
    
    
    
?>
