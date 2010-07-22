<?php
	
    if(!defined('EEC_BASE_PATH'))
    {
        die('The define: EEC_BASE_PATH could not be found. It must point to the path where the EEC Core.php file is located');
    }
    
    if(!defined('EEC_MODULE_PATH'))
    {
        die('The define: EEC_MODULE_PATH could not be found. It must point to the folder where your modules are located');
    }
	
	require_once EEC_BASE_PATH . 'classes/REST_handling.php';		// Class that allows for rest like url's with additions.
	require_once EEC_BASE_PATH . 'classes/Validator.php';	// Class that allows for validation. -- "replace" with a class that uses the filter extension?
	require_once EEC_BASE_PATH . 'classes/TemplateManager_Dwoo.php'; // Include dwoo as the template manager.
    
	class Core
	{
		// The singlethon var
		private static $_oCore;
        
        // URL array filled by getUrl
        private $_aUrl;
		
		// The object storage -- not using SplObjectStorage since it doesn't seem to be right for what i want.
		private $_aDataContainer = array();
		
		private $_sLoadedByModule = null;
        
		/**
		* Constructor that adds some default EEC components.
		*/
		public function __construct()
		{
			$this->set('rest_handling',	        REST_handling::getInstance());
            $this->set('validator',             new Validator());
            $this->set('template_manager',      new TemplateManager_Dwoo());
		}
		
		/**
		* Singlethon function to get the same router object
		*/
		public static function getInstance()
		{
			if(!isset(self::$_oCore))
			{
				self::$_oCore = new Core();
			}
			return self::$_oCore;
		}
		
		public function handleUrl()
		{
            loadModule($this->get("rest_handling")->getModule());   
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
        * setUrl function will be used to compose SEO friendly url's or argument based url's
        */
        public function setUrl($sModule = null, $sSubpath = '', $sItem = '')
        {
            if(is_null($sModule))
            {
                //die('You must provide a module to the setUrl EEC Core function.');
                $this->get('rest_handling')->runFilters();
            }
            else
            {
                $this->get('rest_handling')->runFilters($sModule, $sSubpath, $sItem);
            }
        }
        
        /**
        * getUrl function returns the generated data from setUrl
        */
        public function getUrl()
        {
            $this->_aUrl = array();
            $this->_aUrl['seo'] = preg_replace(array('/(\/){1,}/'), array('/'), implode('/', array($this->get('rest_handling')->getModule(), $this->get('rest_handling')->getSubPath(), $this->get('rest_handling')->getItem())));
            $this->_aUrl['arg'] = "mModule=" . $this->get('rest_handling')->getModule() . '&mSubPath=' . $this->get('rest_handling')->getSubPath() . '&mItem=' . $this->get('rest_handling')->getItem();;
            return $this->_aUrl;
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
            // First set this since it's gonna be overwritten by the functions below
            $this->setLoadedByModule($this->get('rest_handling')->getModule());
            $sNewModule = $this->get('rest_handling')->getSubPath();
            
            // If there is only one "module" in the subPath we detect and use that here.
            if(strpos('/', $sNewModule) === false && strlen($sNewModule) > 2)
            {
                $sNewModule = $this->get('rest_handling')->getSubPath();
                $this->setUrl($sNewModule, '', $this->get('rest_handling')->getItem());
            }
            else
            {
                $sNewModule = stristr('/', $this->get('rest_handling')->getSubPath(), true);
                $this->setUrl($sNewModule, stristr('/', $this->get('rest_handling')->getSubPath()), $this->get('rest_handling')->getItem());
            }
            
            loadModule($sNewModule);
        }
	}
	
	/**
     * loadModule function. This function loads a given module.
     * Seperated from core to prevent modules to be loaded inside the Core class thus having all the Core class details.
     */
	function loadModule($sModule = null)
	{
        if(!is_null($sModule) || strlen($sModule) < 3)
        {
            $sModulePath = EEC_MODULE_PATH . $sModule . '/' . $sModule . '.php';
            if(!file_exists($sModulePath))
            {
                die('The module: ' . $sModule . ' could not be loaded.');
            }
            require_once $sModulePath;
        }
        else 
        {
            die('No module provided for the loadModule function.');
        }
    }
	
?>
