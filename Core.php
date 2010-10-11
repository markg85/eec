<?php
	
    if(!defined('EEC_BASE_PATH'))
    {
        die('The define: EEC_BASE_PATH could not be found. It must point to the path where the EEC Core.php file is located');
    }
    
    if(!defined('EEC_MODULE_PATH'))
    {
        die('The define: EEC_MODULE_PATH could not be found. It must point to the folder where your modules are located');
    }
	
	require_once EEC_BASE_PATH . 'classes/Database.php'; // Database class
	require_once EEC_BASE_PATH . 'classes/REST_handling.php';       // Class that allows for rest like url's with additions.
	require_once EEC_BASE_PATH . 'classes/Validator.php';	// Class that allows for validation. -- "replace" with a class that uses the filter extension?
	require_once EEC_BASE_PATH . 'classes/TemplateManager_Dwoo.php'; // Include dwoo as the template manager.
	require_once EEC_BASE_PATH . 'classes/cache.php'; // Caching class that can use: APC, file and memcache(d)
	require_once EEC_BASE_PATH . 'classes/config.php'; // Config class to store configuration values
	require_once EEC_BASE_PATH . 'classes/ACL.php'; // Config class to store configuration values
    
	class Core
	{
		// The singlethon var
		private static $_oCore;
        
        // URL array filled by getUrl
        private $_aUrl;
		
		// The object storage -- not using SplObjectStorage since it doesn't seem to be right for what i want.
		private $_aDataContainer = array();
		private $_aModuleDataContainer = array();
		
		private $_sLoadedByModule = null;
        
        private $_aLog = array();
        private $_aAdminFoorterHooks = array();
        
        const Warning   = 0;
        const Error     = 1;
        const Notice    = 2;
        
		/**
		* Constructor that adds some default EEC components.
		*/
		public function __construct()
		{
			$this->set('database',              EEC_Database::getInstance());
			$this->set('rest_handling',         REST_handling::getInstance());
            $this->set('validator',             new Validator());
            $this->set('template_manager',      new TemplateManager_Dwoo());
            $this->set('config',                new EEC_Config());
            $this->set('cache',                 new EEC_Cache());
            $this->set('acl',                   new EEC_ACL());
            
            // Set a default timezone
            date_default_timezone_set('Europe/Amsterdam');
		}
		
		/**
		* Singlethon function to get the same core object
		*/
		public static function getInstance()
		{
			if(!isset(self::$_oCore))
			{
				self::$_oCore = new Core();
			}
			return self::$_oCore;
		}
		
		public function handleUrl($sDefaultModule = 'index', $sGroup = 'guest', $aCrud = array('read'))
		{
            $sModulePath = EEC_MODULE_PATH . $this->get("rest_handling")->getModule() . '/' . $this->get("rest_handling")->getModule() . '.php';
            $sModuleConfigPath = EEC_MODULE_PATH . $this->get("rest_handling")->getModule() . '/config.php';
            $sModuleConfigClassName = $this->get("rest_handling")->getModule() . '_config';
            $sDefaultPath = EEC_MODULE_PATH . $sDefaultModule . '/' . $sDefaultModule . '.php';
            
            $bHasPermission = false;
            
            if(defined("ADMIN_AREA") || $this->get('acl')->isAllowed($sGroup, array($this->get("rest_handling")->getModule()), $aCrud))
            {
                $bHasPermission = true;
            }
            else
            {
                var_dump('Warning! No permission on the module: ' . $this->get("rest_handling")->getModule());
            }
            
            /*
             * pseudo code
             * 
             * 1. check in the database if the requested module is there and grab the module settings
             * 2. then check if the current user group has access to that module
             * 3. if it has access then load it otherwise load a default module (like the index page)
             * 
             * All current checks below should go away and replaced by the above mentioned pseudo code.
             */
            
            // Load the current module
            if($bHasPermission && file_exists($sModuleConfigPath) && file_exists($sModulePath) && loadModule($sModuleConfigPath))
            {
                // Load the configuration to see if we are allowed to use this module...
                //$oModule = new $sModuleConfigClassName();
                //var_dump($oModule);
                loadModule($sModulePath);
            }
            // Try to load the default module if the current module failed
            elseif(file_exists($sDefaultPath))
            {
                loadModule($sDefaultPath);
            }
            // And if this also fails we can print a message with some explenation.
            else 
            {
                //die('The default module nor the current module could be loaded!');
            }
        }
		
		/**
		* Get function that returns a Core component object
		*/
		public function get($sCoreComponentName = null)
		{
			if(isset($this->_aDataContainer[$sCoreComponentName]) && is_object($this->_aDataContainer[$sCoreComponentName]))
			{
				return $this->_aDataContainer[$sCoreComponentName];
			}
			else
            {
                die("The provided component: " . $sCoreComponentName . " isn't registered!");
            }
			return false;
		}
		
		/**
		* Set function that adds a given class to the EEC Core.
        * This must be used by plugins to register their class.
		*/
		public function set($sCoreComponentName = null, $sCoreComponent = null, $aDetails = null)
		{
			if(is_array($aDetails))
            {
                if(isset($aDetails['requiredExtensions']) && is_array($aDetails['requiredExtensions']))
                {
                    foreach($aDetails['requiredExtensions'] as $sReqExtension)
                    {
                        if($this->get($sReqExtension) === false)
                        {
                            die("ERROR : The extension: " . $sCoreComponentName . " requires the extension: " . $sReqExtension . ", but that extension isn't loaded!");
                        }
                        
                        // The extension seems to be required thus run it's init function if available
                        if(method_exists(array($this->get($sReqExtension), 'init')) === false)
                        {
                            call_user_func(array($this->get($sReqExtension), 'init'));
                        }
                    }
                }
            }

            if(!isset($this->_aDataContainer[$sCoreComponentName]))
			{
				$this->_aDataContainer[$sCoreComponentName] = $sCoreComponent;
			}
			return false;
		}
		
		/**
		* Del function to delete a stored object. Should not be used but provided for the rare cases.
		*/
		public function del($sCoreComponentName = null)
		{
			if(isset($this->_aDataContainer[$sCoreComponentName]))
			{
				unset($this->_aDataContainer[$sCoreComponentName]);
			}
			return false;
		}
        
        /**
        * validateValue function validates the input and if true returns the input otherwise returns null
        * Also a special fallback param is usable here which can be used for example in forms where wrong data is submitted thus the
        * old data should be used which is what is returned here when the validator check fails and the fallbackValue is
        * filled in.
        */
        public function validateValue($mValue, $sValidator, $mFallbackValue = null)
        {
            if(method_exists($this->get('validator'), $sValidator) || $this->get('validator')->customFilterExists($mValue, $sValidator))
            {
                if(call_user_func(array($this->get('validator'), $sValidator), $mValue) == true || $this->get('validator')->callCustomFilter($mValue, $sValidator) == true)
                {
                    return $mValue;
                }
            }
            if(!is_null($mFallbackValue))
            {
                return $mFallbackValue;
            }
            return null;
        }
        
        /**
        * getLoadedByModule function. This function returns the module that loaded the current module.
        */
        public function getLoadedByModule()
        {
            return $this->_sLoadedByModule;
        }
        
        /**
         * setLoadedByModule function. This function gets set when some module loads some other module.
         * For example for API purposes. Then you can have the "news" module and the "rest" module. rest
         * meaning that the output will be usable for api's like json or xml-rpc. Now when news is called
         * through the rest module (like so: http://url/rest/neuws/1) then news can follow a different
         * code path for rest specific tasks.
         */
        public function setLoadedByModule($sModule)
        {
            $this->_sLoadedByModule = $sModule;
        }
        
        /**
         * shiftAndLoadModule function. This function moves the the first subPath part (till the first "/")
         * to the module part thus allowing one module to load another one.
         */
        public function shiftAndLoadModule()
        {
            $sNewModule = $this->get('rest_handling')->getSubPath();
            
            // If the subPath is empty we can't continue with this function so return.
            if($sNewModule == '')
            {
                return;
            }
            
            // First set this since it's gonna be overwritten by the functions below
            $this->setLoadedByModule($this->get('rest_handling')->getModule());
            
            // If there is only one "module" in the subPath we detect and use that here.
            if(strpos($sNewModule, '/') === false && strlen($sNewModule) > 2)
            {
                $this->get('rest_handling')->setUrl($sNewModule, '', $this->get('rest_handling')->getItem());
            }
            else
            {
                $sNewModule = stristr($this->get('rest_handling')->getSubPath(), '/', true);
                $this->get('rest_handling')->setUrl($sNewModule, stristr('/', $this->get('rest_handling')->getSubPath()), $this->get('rest_handling')->getItem());
            }
            
            $sNewModulePath = EEC_MODULE_PATH . $sNewModule . '/' . $sNewModule . '.php';
            
            // Try to load the shifted module if the shifted module exists otherwise do nothing
            if(file_exists($sNewModulePath))
            {
                loadModule($sNewModulePath);
            }
        }
        
        /**
         * log adds a log enty to the log array. Logs are to be provided in printf format.
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
        
        /**
         * !!! this function should not be here, still deciding where to put this !!!
         * set a hook path which will be used in the admin panel
         */
        public function setAdminFooterHook($sHookTemplateIncludePath)
        {
            if(file_exists($sHookTemplateIncludePath))
            {
                $this->_aAdminFoorterHooks[] = $sHookTemplateIncludePath;
            }
        }
        
        /**
         * !!! this function should not be here, still deciding where to put this !!!
         * return all hook paths.
         */
        public function getAdminFooterHooks()
        {
            return $this->_aAdminFoorterHooks;
        }
        
        /**
         * setModuleData opens the file containing the module data class and puts it in a array which is then callable by getModuleData.
         * This is a generic way to register all module data classes in such a way that any module can access data from any other module.
         */
        public function setModuleData($sModule)
        {
            if(isset($this->_aModuleDataContainer[$sModule]))
            {
                die('You tried to register the module: ' . $sModule . ', but it\'s already registered!');
            }
            
            // Check if the module exists in the module folder
            if(!is_dir(EEC_MODULE_PATH . $sModule))
            {
                die('The module: ' . $sModule . ' doesn\'t seem to be existing in the modules folder!');
            }
            elseif(file_exists(EEC_MODULE_PATH . $sModule . '/' . $sModule . '.php'))
            {
                // oke, the module data file seems to be here.. Check and see if it contains a data class.
                require_once EEC_MODULE_PATH . $sModule . '/data.php';
                $sDataObjectName = $sModule . "_data";
                $oData = new $sDataObjectName();
                
                if($oData instanceof EEC_Data_Interface)
                {
                    var_dump($sModule . ' is now loaded as module data! (Core.php)');
                    $this->_aModuleDataContainer[$sModule] = $oData;
                }
            }
        }
        
        /**
         * getModuleData allows any module to access data from any other module as long as there are registered using "setModuleData".
         */
        public function getModuleData($sModule)
        {
            if(isset($this->_aModuleDataContainer[$sModule]))
            {
                return $this->_aModuleDataContainer[$sModule];
            }
            else
            {
                // So it's not registered? Try to load it anyway (lazy loading)
                $this->setModuleData($sModule);
                
                // If it now fails then it really just fails!
                if(isset($this->_aModuleDataContainer[$sModule]))
                {
                    return $this->_aModuleDataContainer[$sModule];
                }
                die('You tried to get the module: ' . $sModule . ', but it\'s not registered!');
            }
        }
	}
	
	/**
     * loadModule function. This function loads a given module.
     * Seperated from core to prevent modules to be loaded inside the Core class thus having all the Core class details.
     * Right now just a wrapper for require_once ... perhaps not the best way to do this.
     */
	function loadModule($sModule = null)
	{
        require_once $sModule;
        return true;
    }
?>
