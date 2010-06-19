<?php
	
	if(!defined('EEC_BASE_PATH'))
	{
		die('The define: EEC_BASE_PATH could not be found. It must point to the path where the EEC Core.php file is located');
	}
	
	require_once EEC_BASE_PATH . 'classes/REST.php';		// Class that allows for rest like url's with additions.
	require_once EEC_BASE_PATH . 'classes/Validator.php';	// Class that allows for validation. -- "replace" with a class that uses the filter extension?
	
	class Core
	{
		// The singlethon var
		private static $_oCore;
		
		// The object storage -- not using SplObjectStorage since it doesn't seem to be right for what i want.
		private $_aDataContainer = array();
		
		/**
		* Constructor that adds some default EEC components.
		*/
		public function __construct()
		{
			$this->set('rest', 			new REST());
			$this->set('validator', 	new Validator());
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
		* Set function that adds components to the EEC Core. This is likely used by plugins.
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
	}
	
?>
