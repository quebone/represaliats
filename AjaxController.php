<?php
require_once "init.php";

/**
 * Crida el la funció del controlador segons les variables POST obtingudes
 */

if (isset($_POST['function']) && isset($_POST['caller'])) {
	$controller = $_POST['caller'] . "Controller";
	$ac = new AjaxController($controller);
	$ac->loadFunction($_POST['function'], $_POST);
}

class AjaxController
{
    const NAMESPACE = "Represaliats\\Presentation\\Controller\\";
    private $controller;
	private $defaultController;
	
	function __construct($controllerName) {
	    $controllerName = self::NAMESPACE . $controllerName;
	    if (class_exists($controllerName)) {
	        $this->controller = new $controllerName();
	    } else {
	        $this->controller = null;
	    }
	    $defaultControllerName = self::NAMESPACE . "Controller";
	    $this->defaultController = new $defaultControllerName();
	}

	// loads a function from a class
	public function loadFunction($fName, $vars)
	{
	    if ($this->controller != null && method_exists($this->controller, $fName)) {
            echo $this->controller->{$fName}($vars);
	    } else if (method_exists($this->defaultController, $fName)) {
	        echo $this->defaultController->{$fName}($vars);
	    } else {
	        echo false;
	    }
	}
}
