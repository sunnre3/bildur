<?php

namespace output\controller;

require_once('./output/controller/IController.php');
require_once('./user/model/UserDAL.php');
require_once('./register/view/Register.php');
require_once('./register/model/Register.php');

class Register implements IController {
	/**
	 * A RegisterModel to handle our
	 * registration process.
	 * @var [type]
	 */
	private $model;

	/**
	 * A RegisterView to show form
	 * and retrieves user input.
	 * This RegisterView will also implement
	 * an interface to be able to observe
	 * our model.
	 * @var \register\view\Register
	 */
	private $view;

	/**
	 * Initiates objects.
	 */
	public function __construct() {
		//RegisterModel.
		$this->model = new \register\model\Register();
		
		//RegisterView.
		$this->view = new \register\view\Register();
	}

	/**
	 * From interface
	 * Runs registration
	 * @return void
	 */
	public function run() {
		//If the user just submitted his
		//credentials.
		if($this->view->isPOSTSubmit()) {
			try {
				//Get the user from our view.
				$user = $this->view->getUser();

				//Send them to the model class to be saved.
				$this->model->registerUser($user);
			}

			catch(\Exception $e) {
				//Call registerFailure() on our view.
				$this->view->registerFailure();
				//throw $e;
			}
		}
	}

	/**
	 * Returns the registration form.
	 * @return string HTML
	 */
	public function getContent() {
		return $this->view->getRegisterForm();
	}
}