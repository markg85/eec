<?php
    
    /**
     * EEC_Database provides a class that talks to the database.
     * Right now it talks directly without a database abstraction.
     * 
     * The only purpose for this class right now is to have a W.I.P. database class that hides away the need to create queries.
     */
    class EEC_Database extends mysqli
    {
        // The singlethon var
        private static $_oDatabase;
        
        /**
        * Singlethon function to get the same database object
        */
        public static function getInstance()
        {
            if(!isset(self::$_oDatabase))
            {
                self::$_oDatabase = new EEC_Database(EEC_MYSQL_HOST, EEC_MYSQL_USER, EEC_MYSQL_PASS, EEC_MYSQL_DATB);
            }
            return self::$_oDatabase;
        }
        
        public function __construct($host, $user, $pass, $db)
        {
            parent::__construct($host, $user, $pass, $db);
            
            if (mysqli_connect_error())
            {
                // attempt to create a new database
                $this->createDatabase(EEC_MYSQL_DATB);
                
                die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
            }
        }
    }
    
?>