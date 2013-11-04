<?php

namespace output\controller;

require_once('./output/controller/IController.php');
require_once('./user/model/UserDAL.php');
require_once('./register/model/Register.php');
require_once('./login/model/Login.php');
require_once('./register/view/Register.php');
require_once('./application/view/AppView.php');

class Register implements IController {
	/**
	 * A RegisterModel to handle our
	 * registration process.
	 * @var [type]
	 */
	private $registerModel;

	/**
	 * A RegisterView to show form
	 * and retrieves user input.
	 * This RegisterView will also implement
	 * an interface to be able to observe
	 * our model.
	 * @var \register\view\Register
	 */
	private $registerView;

	/**
	 * An AppView can do things
	 * as redirecting.
	 * @var \application\view\AppView
	 */
	private $appView;

	/**
	 * Initiates objects.
	 */
	public function __construct() {
		//RegisterModel.
		$this->registerModel = new \register\model\Register();
		
		//RegisterView.
		$this->registerView = new \register\view\Register();

		//AppView.
		$this->appView = new \application\view\AppView();
	}

	/**
	 * From interface
	 * Runs registration
	 * @return void
	 */
	public function run() {
		//If the user just submitted his
		//credentials.
		if($this->registerView->isPOSTSubmit()) {
			try {
				//Get the user from our view.
				$user = $this->registerView->getUser();

				/** 
				 * Register a LoginModel as an observer
				 * to the RegisterModel so when a user
				 * successfuly registers he will be automtically
				 * logged in.
				**/

				//First create a loginModel.
				$loginModel = new \login\model\Login();

				//Then register it.
				$this->registerModel->registerObserver($loginModel);
				

				//Send them to the model class to be saved.
				$this->registerModel->registerUser($user);

				//When we are done, redirect user to the
				//front page.
				$this->appView->redirectToFrontPage();
			}

			catch(\Exception $e) {
				//Call registerFailure() on our view.
				$this->registerView->registerFailure();
			}
		}
	}

	/**
	 * Returns the registration form.
	 * @return string HTML
	 */
	public function getContent() {
		return $this->registerView->getRegisterForm();
	}
}