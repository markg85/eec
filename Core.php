<?php
	
	if(!defined('EEC_BASE_PATH'))
	{
		die('The define: EEC_BASE_PATH could not be found. It must point to the path where the EEC Core.php file is located');
	}
	
	require_once EEC_BASE_PATH . 'classes/REST.php';		// Class that allows for rest like url's with additions.
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
		
		/**
		* Constructor that adds some default EEC components.
		*/
		public function __construct()
		{
			$this->set('rest', 			        REST::getInstance());
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
		
		/**
		* Get function that returns a Core component object
		*/
		public function get($sCoreComponentName = null)
		{
			if(isset($this->_aDataContainer[$sCoreComponentName]) && is_object($this->_aDataContainer[$sCoreComponentName]))
			{
				return $this->_aDataContainer[$sCoreComponentName];
			}
			return false;
		}
		
		/**
		* Set function that adds a given class to the EEC Core.
        * This must be used by plugins to register their class.
		*/
		public function set($sCoreComponentName = null, $sCoreComponent = null)
		{
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
                $this->get('rest')->runFilters();
            }
            else
            {
                $this->get('rest')->runFilters($sModule, $sSubpath, $sItem);
            }
        }
        
        /**
        * getUrl function returns the generated data from setUrl
        */
        public function getUrl()
        {
            $this->_aUrl = array();
            $this->_aUrl['seo'] = preg_replace(array('/(\/){1,}/'), array('/'), implode('/', array($this->get('rest')->getModule(), $this->get('rest')->getSubPath(), $this->get('rest')->getItem())));
            $this->_aUrl['arg'] = "mModule=" . $this->get('rest')->getModule() . '&mSubPath=' . $this->get('rest')->getSubPath() . '&mItem=' . $this->get('rest')->getItem();;
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
	}
	
?>
