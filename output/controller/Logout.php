<?php

namespace output\controller;

require_once('./output/controller/IController.php');
require_once('./login/model/Login.php');
require_once('./application/view/AppView.php');

class Logout implements IController {
	/**
	 * Used to ask if the user is logged
	 * in and also to log out.
	 * @var \login\model\Login
	 */
	private $loginModel;

	/**
	 * The LoginView handles any persitent login data.
	 * @var \login\view\Login
	 */
	private $loginView;

	/**
	 * This view redirects.
	 * @var \application\view\AppView
	 */
	private $appView;

	/**
	 * Initiate objects.
	 */
	public function __construct() {
		//LoginModel.
		$this->loginModel = new \login\model\Login();

		//LoginView.
		$this->loginView = new \login\view\Login();

		//AppView.
		$this->appView = new \application\view\AppView();
	}

	/**
	 * Performs a logout.
	 * @return void
	 */
	public function run() {
		//Make sure we are even logged in.
		if($this->loginModel->isLoggedIn()) {
			//Log out.
			$this->loginModel->doLogout();

			//Remove any cookies that might
			//be set.
			$this->loginView->removeCookies();

			//Redirect to front-page.
			$this->appView->redirectToFrontPage();
		}
	}

	/**
	 * Since this implements an interface
	 * we have to implement it aswell.
	 *
	 * This, however, doesn't do anything.
	 * @return void
	 */
	public function getContent() {

	}
}