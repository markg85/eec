<?php
	
	class REST_handling
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
			// Run the module, subpath and item filters. None provided thus the php provided values are used.
            $this->runFilters();
		}
		
		public function runFilters($sModule = null, $sSubpath = '', $sItem = '')
		{
            // Only check for the module since that always has to be given.
            // If the $sModule is null, thus none provided, then we use what is provided in the url arguments.
            if(is_null($sModule))
            {
                $sModule    = (!isset($_GET['mModule']))    ? '' : $_GET['mModule'];
                $sSubpath   = (!isset($_GET['mSubPath']))   ? '' : $_GET['mSubPath'];
                $sItem      = (!isset($_GET['mItem']))      ? '' : $_GET['mItem'];
            }
            
            // Strip out double forward slaches so we have just one and filter out any characters that we don't allow in url's
            // Try to use SQL injection now in the URL ^_^
            $this->_sModule  = preg_replace('/[^a-zA-Z0-9_-]/', '', $sModule);
            $this->_sGetItem = preg_replace('/[^a-zA-Z0-9_.-]/', '', $sItem);
            $this->_sSubPath = preg_replace(array('/(\/){1,}/', '/[^\/a-zA-Z0-9_-]/'), array('/', ''), $sSubpath);
            
            // Just for the sake of REST we check those 3 as well. Those can then be used in other modules/plugins.
            switch($_SERVER['REQUEST_METHOD'])
            {
                case 'GET':
                    $this->_bGetAction      = true;
                    if($this->_sGetItem == '')
                    {
                        $this->_bIndexAction = true;
                        $this->_bGetAction = false;
                    }
                    break;
                case 'POST':
                    $this->_bPostAction     = true;
                    break;
                case 'PUT':
                    $this->_bPutAction      = true;
                    break;
                case 'DELETE':
                    $this->_bDeleteAction   = true;
                    break;
            }
        }
		
		/**
		* Singlethon function to get the same router object
		*/
		public static function getInstance()
		{
			if(!isset(self::$_oRest))
			{
				self::$_oRest = new REST_handling();
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
		
		/**
         * getFullUri, this function returns the url part after the module.
         * Made as a convenient function for installing modules since this would otherwise have to be done in every module.
         */
		public function getFullUri()
		{
            if(empty($this->_sSubPath))
            {
                return $this->getItem();
            }
            else
            {
                return $this->_sSubPath . '/' . $this->_sGetItem;
            }
        }
        
        /**
         * This function is also a convenient function for modules since otherwise they would have to explode getFullUri on "/"
         * to find the first next part of the uri after the module.
         */
        public function getFirstAfterModule()
        {
            $sUri = $this->getFullUri();
            $aUri = explode('/', $sUri);
            
            if(!empty($aUri) && is_string($aUri[0]))
            {
                return $aUri[0];
            }
            return '';
        }
	}
	