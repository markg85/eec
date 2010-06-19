<?php
	
	class REST
	{
		// The singlethon var
		private static $_oRest;
		
		// REST Booleans. Those are being changed when needed in the constructor below
		private $_bIndexAction 	= false;
		private $_bGetAction 	= false;
		private $_bPostAction 	= false;
		private $_bPutAction 	= false;
		private $_bDeleteAction = false;
		
		// The values that will be used throughout this application and are being grabbed with respectively: getItem, getModule and getSubPath
		private $_sGetItem 		= '';
		private $_sModule 		= '';
		private $_sSubPath 		= '';
		
		public function __construct()
		{
			$_GET['mModule'] 	= (!isset($_GET['mModule'])) 	? '' : $_GET['mModule'];
			$_GET['mSubPath'] 	= (!isset($_GET['mSubPath'])) 	? '' : $_GET['mSubPath'];
			$_GET['mItem'] 		= (!isset($_GET['mItem'])) 		? '' : $_GET['mItem'];
				
			// First strip out double forward slaches so we have just one. Then filter out any characters that we don't allow in url's
			// Try to use SQL injection now in the URL ^_^
			$this->_sModule	 = preg_replace('/[^a-zA-Z0-9_-]/', '', $_GET['mModule']);
			$this->_sGetItem = preg_replace('/[^a-zA-Z0-9_.-]/', '', $_GET['mItem']);
			$this->_sSubPath = preg_replace(array('/(\/){1,}/', '/[^\/a-zA-Z0-9_-]/'), array('/', ''), $_GET['mSubPath']);
			
			// Just for the sake of REST we check those 3 as well. Those can then be used in other modules/plugins.
			switch($_SERVER['REQUEST_METHOD'])
			{
				case 'POST':
					$this->_bPostAction = true;
					break;
				case 'PUT':
					$this->_bPutAction = true;
					break;
				case 'DELETE':
					$this->_bDeleteAction = true;
					break;
			}
			
			if($this->_sGetItem != '')
			{
				$this->_bGetAction = true;
			}
			
			if($this->_sGetItem == '')
			{
				$this->_bIndexAction = true;
			}
		}
		/**
		* Singlethon function to get the same router object
		*/
		public static function getInstance()
		{
			if(!isset(self::$_oRest))
			{
				self::$_oRest = new REST();
			}
			return self::$_oRest;
		}
		
		public function isIndex()
		{
			if($this->_bIndexAction === true)
			{
				return true;
			}
			return false;
		}
		
		public function isGet()
		{
			if($this->_bGetAction === true)
			{
				return true;
			}
			return false;
		}
		
		public function isPost()
		{
			if($this->_bPostAction === true)
			{
				return true;
			}
			return false;
		}
		
		public function isPut()
		{
			if($this->_bPutAction === true)
			{
				return true;
			}
			return false;
		}
		
		public function isDelete()
		{
			if($this->_bDeleteAction === true)
			{
				return true;
			}
			return false;
		}
		
		public function getModule()
		{
			return $this->_sModule;
		}
		
		public function getItem()
		{
			return $this->_sGetItem;
		}
		
		public function getSubPath()
		{
			return $this->_sSubPath;
		}
	}
	