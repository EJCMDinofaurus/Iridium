<?php
	require_once("load.php");

	function arrayUrlDecode($array)	{
		return array_map("urldecode", $array);
	}

    $url = array();
    if(!empty($_GET["route"])){
        $url = explode("/", $_GET["route"]);
		unset($_GET["route"]);
    }

    $urlIndex = 0;
    $urlSize = count($url);

    if($urlSize === 0){
        Controller::load();
        Controller::getInstance()->loadAction();
    }
    if($urlSize > 0){
        $urlSlice = $url[$urlIndex];
        $controllerName = ucfirst($urlSlice);

        if(Controller::exists($controllerName)){
            Controller::load($controllerName);
            $urlIndex++;
        }else{
            Application::send404();
        }
        if($urlSize > $urlIndex){
            $urlSlice = $url[$urlIndex];

            // esse if é uma gambiarra para evitar fazer muitas modificações no código.
            // corrigir algum dia...
            if(!empty($urlSlice)){
                $actionName = strtolower($urlSlice[0]) . substr($urlSlice, 1);
            } else {
                $actionName = $urlSlice;
            }

            if(Controller::getInstance()->actionExists($actionName)){
                $urlIndex++;
                $parameters = arrayUrlDecode(array_slice($url,$urlIndex));
                Controller::getInstance()->loadAction($actionName,$parameters);
            }
            else if(empty($urlSlice)){
                Controller::getInstance()->loadAction();
            }
            else{
				Application::send404();
            }

            if(empty($url[$urlSize - 1])){
				unset($url[count($url) -1]);
                Application::send301(implode("/", $url));
            }
        }
        else{
            Controller::getInstance()->loadAction();
        }
    }