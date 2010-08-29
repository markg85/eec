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
        
        public function createTable($sTableName, array $aColumns)
        {
            $template = "CREATE TABLE `".$sTableName."` (
                          `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY
                        ) ENGINE = INNODB CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT =  'ACL Table';";
            
            $this->query($template);
        }
        
        public function dropTable($sTableName)
        {
            $template = "drop table " . $sTableName . ";";
            $this->query($template);
        }
        
        public function createColumns(array $aColumn)
        {
            
        }
        
        public function dropColumns(arras $aColumns)
        {
            
        }
        
        public function createIndex($sIndexName, array $aColumns)
        {
            
        }
        
        public function dropIndex($sIndexName)
        {
            
        }
        
        public function createForeignKey($sFKName, $sColumnFrom, $sColumnTo)
        {
            
        }
        
        public function dropForeignKey($sFKName)
        {
            
        }
        
        public function getData($sTable, $aColumns)
        {
            //return the data...
        }
        
    }
    
?>