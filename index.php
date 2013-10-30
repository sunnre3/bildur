<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('html_errors', 1);

echo "<h3>DON'T FORGET TO REMOVE.</h3>";

function debug($item) {
	echo "<pre>";
	var_dump($item);
	echo "</pre>";
}

/**
 * THIS IS THE FRONT CONTROLLER.
 * THIS WILL BE THE ONLY FILE IN THE ROOT DIRECTORY.
 * MAKE SURE ALL OTHER REQUESTS GETS REROUTED HERE.
 *
 * THIS IS TO MAKE SURE ALL INCOMING REQUESTS MAY BE HANDLED
 * PROPERLY.
 *
 * WHAT HAPPENDS HERE IS WE DEFINE SOME VARIABLES WHICH
 * WE CAN HOPEFULLY USE THROUGHOUT THE WHOLE FRAMEWORK
 * AND WE WON'T HAVE TO DEAL WITH ANNOYING FILE PATHS ETC.
 *
 * THIS IS ALSO WHERE ALL THE MAGIC HAPPENDS.
 *
 * LASTLY, YOU WILL PROBABLY AND HOPEFULLY NEVER HAVE TO
 * CHANGE ANYTHING IN THIS FILE SO BEST LET IT BE.
 */


define("BASE_PATH", realpath(dirname(__FILE__)) . "/" , true);
define("STYLESHEET_PATH", "output/" . "stylesheet/");

require_once("./database/config.php");
require_once("./application/controller/Application.php");

$appController = new \application\controller\Application();
$appController->runApp();