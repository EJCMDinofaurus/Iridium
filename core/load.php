<?php
	//Include das Constantes
	require_once("constants.php");
	require_once("Application.php");
	require_once("IridiumException.php");

	//set error reporting to everything
	error_reporting(E_ALL|E_STRICT);

	//Converting Errors into Exceptions
	function custom_error_handler($number, $message, $file, $line)
	{
		//error_reporting será '0' se '@' for usado com a tentativa de suprimir o erro
		//esse if garante que essa funçao respeite o '@'
		if(error_reporting() == 0)
		{
			return;
		}
		throw new ErrorException($message, $number, 0, $file, $line);
	}
	set_error_handler("custom_error_handler");

	/*
	 * Printa todas as exceptions de maneira mais legível
	 * e oculta exceptions dependendo do ambiente atual.
	 * Por padrão 'development' vai mostrar todas as exceptions, mas 'testing' e 'production' vão ocultar
	*/
	function custom_exception_handler($exception)
	{
		// esse try garante que uma exception lançada dentro do excetion handler não apareça como
		// tendo sido lançada na linha 0
		try
		{
			// só mostra as exceptions em ambientes diferentes de 'production' e 'testing'
			if (Application::getEnvironment() != PRODUCTION_ENVIRONMENT
				&& Application::getEnvironment() != TESTING_ENVIRONMENT)
			{
				echo
				"<html>
				<h2>Unhandled ".get_class($exception)."</h2>
				<h3>Message:</h3>
				<pre>" . $exception->getMessage() . "</pre>
				<h3>Location:</h3>
				<pre>" . $exception->getFile() . " on line " . $exception->getLine() . "</pre>
				<h3>Stack Trace:</h3>
				<pre>" . $exception->getTraceAsString() . "</pre>
				<h3>__toString:</h3>
				<pre>" . $exception->__toString() . "</pre>
				</html>"
				;
			}
			else
			{
				Application::send500();
			}
		}
		catch(Exception $ex)
		{
			echo "Unhandled ".get_class($ex)." throw inside the exception handler:<br>". $ex->getMessage()."<br> at ".$ex->getFile() . " on line " . $ex->getLine();
            exit();
		}
	}
	set_exception_handler("custom_exception_handler");

	//Autoload das classes do sistema

	function loadClasses($className)
	{
		if(array_key_exists($className, Application::$classToFileDictionary))
		{
			$filePath = Application::$classToFileDictionary[$className];
		}
		else
		{
			$filePath = str_replace("_", DIRECTORY_SEPARATOR, $className).".php";
		}

		foreach(Application::$includePath as $includePath)
		{
			$completePath = $includePath.$filePath;
			if(file_exists($completePath))
			{
				include($completePath);
				return true;
			}
		}
        return false;
	}
	spl_autoload_register("loadClasses");

	Application::addToIncludePath(LOCAL_ROOT.CORE_FOLDER);
	Application::addToIncludePath(LOCAL_ROOT.CONTROLLER_FOLDER);
	Application::addToIncludePath(LOCAL_ROOT.DATAACCESS_FOLDER);
	Application::addToIncludePath(LOCAL_ROOT.MODEL_FOLDER);
	Application::addToIncludePath(LOCAL_ROOT.LIBRARIES_FOLDER);

	//Include do arquivo de Configuração do Projeto
	require_once("config.php");

	//Iniciando a Session
	session_name(Application::getSessionName());
	session_start();