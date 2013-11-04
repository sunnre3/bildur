<?php

namespace application\controller;

class Session {
	/**
	 * LoginModel is needed to actually log in
	 * a user if we find one.
	 * @var \login\model\Login
	 */
	private $loginModel;

	/**
	 * LoginView can retrieve the saved data
	 * from our client.
	 * @var \login\view\Login
	 */
	private $loginView;

	/**
	 * Initiate objects and starts a
	 * session.
	 */
	public function start() {
		//LoginModel.
		$this->loginModel = new \login\model\Login();

		//LoginView.
		$this->loginView = new \login\view\Login();

		//Start the session.
		session_start();

		//If the client has saved login data,
		//try them.
		if($this->loginView->hasSavedLoginData())
			$this->login();
	}

	/**
	 * This method retrieves the credentials
	 * from the loginView and makes sure they are
	 * valid before we log in the user.
	 * @return void
	 */
	private function login() {
		try {
			//Get a User object from the loginView.
			$user = $this->loginView->getUser();

			//Login the user.
			$this->loginModel->doLogin($user);
		}

		catch(\Exception $e) {
			//Remove the cookies.
			$this->loginView->removeCookies();
		}
	}
}