<?php

namespace application\controller;

require_once('./application/model/Setup.php');
require_once('./application/controller/Router.php');
require_once('./output/model/Stylesheet.php');
require_once('./output/model/Script.php');
require_once('./output/view/HTMLPage.php');
require_once('./application/controller/Session.php');

class Application {
	/**
	 * @var \application\model\Setup.
	 */
	private $setup;

	/**
	 * @var \application\model\Router.
	 */
	private $router;

	/**
	 * Application specifik class for session.
	 * @var \application\controller\Session
	 */
	private $session;

	/**
	 * @var \common\view\HTMLPage.
	 */
	private $htmlPage;

	/**
	 * Creates objects.
	 */
	public function __construct() {
		//Create a Setup object
		$this->setup = new \application\model\Setup();

		//Initiate $session.
		$this->session = new \application\controller\Session();
	}

	/**
	 * This is where we output our markup.
	 * @param  \output\controller\IController
	 * @return void
	 */
	private function output(\output\controller\IController $controller) {
		//Run.
		$controller->run();

		//Get the correct content.
		$content = $controller->getContent();

		//Render page.
		$this->htmlPage->renderPage($content);
	}

	/**
	 * This method makes sure we are properly set up
	 * and if we aren't we run the appropriate methods
	 * to make sure we are before we continue.
	 *
	 * This is also the startpoint for our app.
	 * @return void
	 */
	public function runApp() {
		//FOR DEVELOPING PUROSES
		//$this->setup->clearDB();

		//First check if everyone is set up.
		if(!$this->setup->isSetUp())
			//If it isn't, make sure it is.
			$this->setup->setup();

		//Start a session.
		$this->session->start();

		//Create a Router object
		$this->router = new \application\model\Router();

		//Create a HTMLPage object
		$this->htmlPage = new \output\view\HTMLPage();

		//Get the controller and
		//send it along.
		$contr = $this->router->getController();
		$this->output($contr);
	}
}