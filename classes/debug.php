<?php
    
    class EEC_Debug
    {
        // The singlethon var
        private static $_oDebug;
        
        /**
        * Singlethon function to get the same debug object
        */
        public static function getInstance()
        {
            if(!isset(self::$_oDebug))
            {
                self::$_oDebug = new EEC_Debug();
            }
            return self::$_oDebug;
        }
        
        
        
    }
    
    
?>
