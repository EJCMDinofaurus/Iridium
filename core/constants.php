<?php

//Environments
define("DEVELOPMENT_ENVIRONMENT", 0);
define("TESTING_ENVIRONMENT", 1);
define("PRODUCTION_ENVIRONMENT", 2);

//Folders and Files paths
define("LOCAL_ROOT", dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR);
define("CONTROLLER_FOLDER", "controller/");
define("CORE_FOLDER", "core/");
define("DATAACCESS_FOLDER", "dataaccess/");
define("LIBRARIES_FOLDER", "libraries/");
define("MODEL_FOLDER", "model/");
define("VIEW_FOLDER", "view/");

//View configuration
define("NEON_DEFAULT_BLOCK_NAME", 'content');
define("VIEW_PAGES_FOLDER", "pages/");
define("VIEW_TEMPLATES_FOLDER", "templates/");

//Default Classes Suffixes
define("CONTROLLER_SUFFIX", "Controller");
