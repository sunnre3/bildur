<?php

namespace output\controller;

require_once('./output/controller/IController.php');
require_once('./login/view/Login.php');
require_once('./login/model/Login.php');
require_once('./application/view/AppView.php');

class Login implements IController {
	/**
	 * The model handles the login.
	 * @var \login\model\Login
	 */
	private $model;

	/**
	 * A view is used to show our login form
	 * and to retrieve userdata.
	 * @var \login\view\Login
	 */
	private $view;

	/**
	 * This view redirects.
	 * @var \application\view\AppView
	 */
	private $appView;

	/**
	 * Initiate objects.
	 */
	public function __construct() {
		//Model.
		$this->model = new \login\model\Login();
		
		//View.
		$this->view = new \login\view\Login();

		//AppView.
		$this->appView = new \application\view\AppView();
	}

	/**
	 * Executes what needs to be done in
	 * order to allow a user to login.
	 * @return void
	 */
	public function run() {
		//If the user pressed submit.
		if($this->view->isPOSTSubmit()) {
			try {
				//Get the User object from the view.
				$user = $this->view->getUser();

				//Try login with the model.
				$this->model->doLogin($user);

				//Redirect to front-page.
				$this->appView->redirectToFrontPage();
			}

			catch(\Exception $e) {
				$this->view->loginFailed();
			//	throw $e;
			}
		}
	}

	/**
	 * Returns HTML for login.
	 * @return string HTML
	 */
	public function getContent() {
		return $this->view->getLoginForm();
	}
}