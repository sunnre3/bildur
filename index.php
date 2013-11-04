<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('html_errors', 1);

function debug($item) {
	var_dump($item);
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

define('BASE_PATH', realpath(dirname(__FILE__)) . '/' , true);
define('STYLESHEET_PATH', 'output/' . 'stylesheets/');
define('SCRIPT_PATH', 'output/' . 'scripts/');
define('UPLOAD_PATH', 'uploads/');

//Set default timezone.
date_default_timezone_set('Europe/Stockholm');

require_once('./lib/password.php');
require_once('./database/config.php');
require_once('./application/default_router_values.php');
require_once('./application/controller/Application.php');

$appController = new \application\controller\Application();
$appController->runApp();