<?php
    
    require_once EEC_BASE_PATH . "classes/Data_Interface.php";
    
    class consolelog_data implements EEC_Data_Interface
    {
        private $_aLog = array();
        
        /**
         * log adds a log enty to the log array. Logs are to be provided in printf format.
         * 
         * $iWarningType can be:
         * - 0 = Warning
         * - 1 = Error
         * - 2 = Notice
         */
        public function log($sModule, $iWarningType, $sMessage)
        {
            $aArgs = func_get_args();
            unset($aArgs[0]);
            unset($aArgs[1]);
            unset($aArgs[3]);

            $oDateTime = new DateTime("now");

            $aLogLine['module']     = $sModule;
            $aLogLine['type']       = $iWarningType;
            $aLogLine['message']    = vsprintf($sMessage, $aArgs);
            $aLogLine['timestamp']  = $oDateTime->getTimestamp();
            $this->_aLog[] = $aLogLine;
        }
        
        /**
         * getLog returns all log lines to be used.. anywhere.
         */
        public function getLog()
        {
            return $this->_aLog;
        }
    }
?> 
