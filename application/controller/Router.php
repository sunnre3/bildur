<?php

namespace application\model;

require_once("./application/view/AppView.php");
require_once("./output/controller/FrontPage.php");

class Router {
	/**
	 * @var \application\view\AppView
	 */
	private $view;

	/**
	 * Initiate object
	 */
	public function __construct() {
		$this->view = new \application\view\AppView();
	}

	/**
	 * Determines which controller should be run.
	 * @return object
	 */
	public function getController() {
		switch($this->view->getAction()) {
			case 'value':
				# code...
				break;
			
			default:
				return new \output\controller\FrontPage();
				break;
		}
	}
}