<?php

class Application
{
	private static $webRoot;
	private static $route;
	private static $environment;
	private static $sessionName;
	private static $viewDefaulPlataform;
	private static $defaultControllerName;
	private static $defaultActionName;
	private static $error404Path;
	private static $error500Path;
	private static $error403Path;
	private static $viewDefaultTemplate;
	private static $viewDefaultTemplateFile;
	private static $defaultDatabaseEnconding;
	private static $errors = array();
	//deveria ser privada mas isso evita que possa ser usada na Load.php
	static $classToFileDictionary = array();
	static $includePath = array();

	public static function setWebRoot($webRoot)
	{
		if($webRoot[strlen($webRoot)-1] != "/")
		{
			throw new IridiumException("O web root da aplicação precisa terminar em '/'.");
		}

		self::$webRoot = $webRoot;
	}

	public static function getWebRoot()
	{
		return self::$webRoot;
	}

    public static function discoverWebRoot()
    {
        if(self::getEnvironment() != PRODUCTION_ENVIRONMENT)
        {
            $path = dirname(dirname($_SERVER["SCRIPT_NAME"]));
            if(strlen($path) != 1)
            {
                $path .= "/";
            }
            self::$webRoot = "http://".$_SERVER["HTTP_HOST"].$path;
        }
        else
            throw new IridiumException("O web root só pode ser descoberto automaticamente fora do PRODUCTION_ENVIRONMENT");
    }

	public static function setDefaultActionName($defaultActionName)
	{
		self::$defaultActionName = $defaultActionName;
	}

	public static function getDefaultActionName()
	{
		return self::$defaultActionName;
	}

	public static function setDefaultControllerName($defaultControllerName)
	{
		self::$defaultControllerName = $defaultControllerName;
	}

	public static function getDefaultControllerName()
	{
		return self::$defaultControllerName;
	}

	public static function setDefaultDatabaseEnconding($defaultDatabaseEnconding)
	{
		self::$defaultDatabaseEnconding = $defaultDatabaseEnconding;
	}

	public static function getDefaultDatabaseEnconding()
	{
		return self::$defaultDatabaseEnconding;
	}

	public static function setEnvironment($environment)
	{
		if(!in_array($environment, array(DEVELOPMENT_ENVIRONMENT, TESTING_ENVIRONMENT, PRODUCTION_ENVIRONMENT)))
			throw new IridiumException("Environment '$environment' não permitido. Por favor defina DEVELOPMENT_ENVIRONMENT, TESTING_ENVIRONMENT ou PRODUCTION_ENVIRONMENT ao objeto da Aplicação.");

		self::$environment = $environment;
	}

	public static function getEnvironment()
	{
		return self::$environment;
	}

	public static function setError404Path($error404Path)
	{
		self::$error404Path = $error404Path;
	}

	public static function getError404Path()
	{
		return self::$error404Path;
	}

	public static function setError500Path($error500Path)
	{
		self::$error500Path = $error500Path;
	}

	public static function getError500Path()
	{
		return self::$error500Path;
	}

	public static function setSessionName($sessionName)
	{
		self::$sessionName = $sessionName;
	}

	public static function getSessionName()
	{
		return self::$sessionName;
	}

	public static function setViewDefaulPlataform($viewDefaulPlataform)
	{
		self::$viewDefaulPlataform = $viewDefaulPlataform;
	}

	public static function getViewDefaulPlataform()
	{
		return self::$viewDefaulPlataform;
	}

	public static function setViewDefaultTemplate($viewDefaultTemplate)
	{
		self::$viewDefaultTemplate = $viewDefaultTemplate;
	}

	public static function getViewDefaultTemplate()
	{
		return self::$viewDefaultTemplate;
	}

	public static function setViewDefaultTemplateFile($viewDefaultTemplateFile)
	{
		self::$viewDefaultTemplateFile = $viewDefaultTemplateFile;
	}

	public static function getViewDefaultTemplateFile()
	{
		return self::$viewDefaultTemplateFile;
	}

	public static function setError403Path($error403Path)
	{
		self::$error403Path = $error403Path;
	}

	public static function getError403Path()
	{
		return self::$error403Path;
	}

	public static function addToIncludePath($path)
	{
		if(is_dir($path))
		{
			self::$includePath[] = $path;
		}
		else
		{
			throw new IridiumException("O caminho '<em>$path</em>' não existe.");
		}
	}

	public static function mapClassToFile($className, $fileName)
	{
		self::$classToFileDictionary[$className] = $fileName;
	}

	public static function setResponseContentType($contentType)
	{
		header("Content-type: ".$contentType);
	}

	public static function redirect($toUrl, $absolute = true)
	{
		$url = ($absolute ? Application::getWebRoot() : "").$toUrl;
		header("Location: $url");
	}

	private static function sendStatusCode($newCode)
	{
		$code = 200;
		if($newCode !== NULL)
		{
			header('X-PHP-Response-Code: '.$newCode, true, $newCode);
			if(!headers_sent())
				$code = $newCode;
		}
		return $code;
	}

	public static function send404()
	{
		self::sendStatusCode(404);
		$arrayError = explode("/",self::getError404Path());
		Controller::load($arrayError[0]);
		Controller::getInstance()->loadAction($arrayError[1]);
		exit();
	}

	public static function send500()
	{
		self::sendStatusCode(500);
		$arrayError = explode("/",self::getError500Path());
		Controller::load($arrayError[0]);
		Controller::getInstance()->loadAction($arrayError[1]);
		exit();
	}

	public static function send301($url){
		self::sendStatusCode(301);
		self::redirect($url);
	}

}
