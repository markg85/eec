<?php
    
    include EEC_BASE_PATH . 'classes/ACL/Role.php';
    include EEC_BASE_PATH . 'classes/ACL/Resource.php';
    
    /**
     * EEC_ACL class managed the ACL possibilities in EEC.
     * The ACL permissions work on the CRUD permissions (Create, Read, Update and Delete) All places in this EEC_ACL class
     * that require CRUD permissions can be written in length in an array like so:
     * array('create', 'read', 'update', 'delete');
     * 
     * Or in a simple manner like so:
     * array('CRUD'); which adds all the permissions as well.
     * 
     * All the ACL roles are stored and fetched to and from the database. EEC_ACL uses the EEC_Cache machanism to 
     * hide away the database queries.
     */
    class EEC_ACL
    {
        private $_oDatabase;
        
        public function __construct()
        {
            $this->_oDatabase = EEC_Database::getInstance();
        }
        
        /**
         * Add a role. For example:
         * addRole(new EEC_ACL_Role('users');
         * addRole(new EEC_ACL_Role('admin');
         * etc... A role should represent a "group" of users or just a single user.
         */
        public function addRole(EEC_ACL_Role $oRole, $sInheritRole = null)
        {
            $this->_oDatabase->query(   "INSERT INTO `acl_roles` (
                                        `role`
                                        )
                                        VALUES (
                                        '".$oRole->getRoleName()."'
                                        );");
        }
        
        /**
         * Drops a role. This function will drop the given role from the database.
         */
        public function dropRole($sRole)
        {
            $this->_oDatabase->query("DELETE FROM `acl_roles` WHERE `role` = ". $sRole);
        }
        
        /**
         * Add a resource. For example:
         * addResource(new EEC_ACL_Resource('news'));
         * addResource(new EEC_ACL_Resource('comments'));
         * etc... A resource should represent a module in EEC. Each module should have one (or more) ACL resource(s).
         */
        public function addResource(EEC_ACL_Resource $oResource)
        {
            $this->_oDatabase->query(   "INSERT INTO `acl_resources` (
                                        `resource`
                                        )
                                        VALUES (
                                        '".$oResource->getResourceName()."'
                                        );");
        }
        
        /**
         * Drop a resource. This function will drop a resource from the database.
         */
        public function dropResource($sResource)
        {
            $this->_oDatabase->query("DELETE FROM `acl_resources` WHERE `resource` = ". $sResource);
        }
        
        /**
         * Grant a user or a group permission to a certain resource (or multiple). The permission can be any of the CRUD values. For example:
         * grant('users', array('news'), array('read'));
         * grant('users', array('news', 'comments'), array('read'));
         * grant('admin', array('news', 'comments'), array('create', 'read', 'update', 'delete'));
         * 
         * Or the fast CRUD notation where you can simply use C,R,U or D in one value like so:
         * grant('admin', array('news', 'comments'), array('CRU')); which would give Create, Read and Update (no delete).
         */
        //public function grant($sRole = null, array $oResource, array $aCRUD)
        public function grant($sRole = null, $sResource, array $aCRUD) // lets keep it simple for now..
        {
            $result = $this->_oDatabase->query("SELECT id FROM acl_roles WHERE role = '".$sRole."';");
            $aRoleId = $result->fetch_array(MYSQLI_ASSOC);
            
            $result = $this->_oDatabase->query("SELECT id FROM acl_resources WHERE resource = '".$sResource."';");
            $iResourcdeId = $result->fetch_array(MYSQLI_ASSOC);
            
            $result = $this->_oDatabase->query("SELECT role_id, resource_id FROM acl_permissions WHERE role_id =".$aRoleId['id']." AND resource_id =".$iResourcdeId['id']." ;");
            $oTestResult = $result->fetch_array(MYSQLI_ASSOC);
            var_dump($oTestResult);
            
            $aNewCrud = $this->crud($aCRUD, false);
            
            $sQuery = "INSERT INTO `acl_permissions` (
                                        `role_id` ,
                                        `resource_id` ,
                                        `create` ,
                                        `read` ,
                                        `update` ,
                                        `delete`
                                        )
                                        VALUES (
                                        '".$aRoleId['id']."',  '".$iResourcdeId['id']."', " . implode(', ', $aNewCrud) . ");";
                                        
            $sUpdateQuery = " UPDATE `acl_permissions` SET  
                        `create` =  ".$aNewCrud['create'].",
                        `read` =  ".$aNewCrud['read'].",
                        `update` =  ".$aNewCrud['update'].",
                        `delete` =  ".$aNewCrud['delete']." WHERE `role_id` = ".$aRoleId['id']." AND `resource_id` =".$iResourcdeId['id'].";";
            
            // Insert if not exists
            if(is_null($oTestResult))
            {
                $this->_oDatabase->query($sQuery);
            }
            // Update
            else
            {
                $this->_oDatabase->query($sUpdateQuery);
            }
        }
        

        
        /**
         * Same as grant only revokes the given permissions when present
         */
        //public function revoke($sRole = null, array $oResource, array $aCRUD)
        public function revoke($sRole = null, $sResource, array $aCRUD) // Again, lets keep it simple for now.
        {
            $result = $this->_oDatabase->query("SELECT id FROM acl_roles WHERE role = '".$sRole."';");
            $aRoleId = $result->fetch_array(MYSQLI_ASSOC);
            
            $result = $this->_oDatabase->query("SELECT id FROM acl_resources WHERE resource = '".$sResource."';");
            $iResourcdeId = $result->fetch_array(MYSQLI_ASSOC);
            
            $aNewCrud = $this->crud($aCRUD, true);
            
            $sQuery = " UPDATE `acl_permissions` SET  
                        `create` =  ".$aNewCrud['create'].",
                        `read` =  ".$aNewCrud['read'].",
                        `update` =  ".$aNewCrud['update'].",
                        `delete` =  ".$aNewCrud['delete']." WHERE `role_id` = ".$aRoleId['id']." AND `resource_id` =".$iResourcdeId['id'].";";

            var_dump($sQuery);
            
            $this->_oDatabase->query($sQuery);
        }
        
        /**
         * Checks a certain user or group against a resource to see if a certain CRUD permission is present. For example:
         * isAllowed('users', array('news'), array('update'));
         * 
         * Checking on multiple resources:
         * isAllowed('users', array('news', 'comments'), array('update'));
         * isAllowed('users', array('news', 'comments'), array('create', 'read', 'update', 'delete'));
         * 
         * The fast crud notation isn't allowed in this function. Please supply the crud values as given in the above example
         */
        public function isAllowed($sRole, $sResource, $aCRUD)
        {
            $result = $this->_oDatabase->query("SELECT id FROM acl_roles WHERE role = '".$sRole."';");
            $aRoleId = $result->fetch_array(MYSQLI_ASSOC);
            
            $result = $this->_oDatabase->query("SELECT id FROM acl_resources WHERE resource IN ('".implode('\',\'', $sResource)."');");
            $iResourcdeId = $result->fetch_all(MYSQLI_ASSOC);
            $idArray = array();
            foreach($iResourcdeId as $aId) {$idArray[] = $aId['id'];};
            
            $result = $this->_oDatabase->query("SELECT `".implode("`, `", $aCRUD)."` FROM acl_permissions WHERE role_id = ".$aRoleId['id']." AND resource_id IN (".implode(',', $idArray).") ;");
            $aAllowedResult = $result->fetch_all(MYSQLI_ASSOC);
            
            if(is_null($aAllowedResult))
            {
                return false;
            }
            else
            {
                // for each resource we're gonna check the acl values with the crud names you provided
                foreach($aAllowedResult as $aCrudResult)
                {
                    foreach($aCRUD as $crudName)
                    {
                        if(!$aCrudResult[$crudName])
                        {
                            return false;
                        }
                    }
                }
                return true;
            }
            
        }
        
        /**
         * CRUD helper function to stranslate CRUD into create, read, update and delete.
         */
        private function crud(array $aCrud, $bReverse = false)
        {
            $aCrudTemplate = array('create' => 0, 'read' => 0, 'update' => 0, 'delete' => 0);
            
            // If reverse is set to true all values (that are provided in $aCrud) will be set to 0 (false). If reverse is set to false it will all be set to 1.
            $iValue = ($bReverse) ? 0 : 1;
            
            $loopVar = $aCrud;
            $iLoopNumber = count($aCrud);
            if(count($aCrud) == 1 && strlen($aCrud[0]) <= 4 && strtolower($aCrud[0]) != "read")
            {
                $loopVar = $aCrud[0];
                $iLoopNumber = strlen($aCrud[0]);
            }
            
            for($i = 0; $i < $iLoopNumber; $i++)
            {
                switch(strtoupper($loopVar[$i]))
                {
                    case 'C':
                    case 'CREATE':
                        $aCrudTemplate['create'] = $iValue;
                        break;
                    case 'R':
                    case 'READ':
                        $aCrudTemplate['read'] = $iValue;
                        break;
                    case 'U':
                    case 'UPDATE':
                        $aCrudTemplate['update'] = $iValue;
                        break;
                    case 'D':
                    case 'DELETE':
                        $aCrudTemplate['delete'] = $iValue;
                        break;   
                }
            }
            return $aCrudTemplate;
        }
    }