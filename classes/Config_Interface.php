<?php

    /**
     * This interface defines the function a module configuration _must_ have.
     * Note that the implementation must have the same name as the module with _config appended in the class name.
     * For example, the Foo module must have a Foo_config class inside the "config.php" file! No config file or no class like that and the module will not be visible!
     */
    interface EEC_Config_Interface
    {
        /**
         * The name of the author creating this module. null for anonymous
         */
        public function getAuthor();
        
        /**
         * The email of the author creating this module. null for anonymous
         */
        public function getEmail();
        
        /**
         * The version of this module. Must be provided in an integer, double or float format
         */
        public function getVersion();
        
        /**
         * The module name. This can be a "human readable" fancy name with spaces and such.
         */
        public function getModuleName();
        
        /**
         * The module name that's being used in the url. This must be a name free of any spaces or special characters. Only A-Z, a-z, _ and 0-9 is allowed in here
         */
        public function getModuleRestName();
        
        /**
         * This gets the module state whether it's enabled or disabled. By default all modules are enabled.
         */
        public function getEnabled();
        
        /**
         * This gets the rest state for the module whether it has special rest capabilities. By default disabled.
         */
        public function getRestEnabled();
        
        /**
         * This gets the menu name for the module. This name will be used in headers or titles.
         */
        public function menuName();
        
        /**
         * This gets the menu entries that the module has.
         */
        public function menuEntries();
        
        /**
         * This function holds the database create statement. Provide "null" for no database create statement.
         */
        public function createTableStatement();
    }
