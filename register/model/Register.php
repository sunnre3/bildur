<?php

namespace register\model;

require_once('./user/model/UserList.php');
require_once('./login/model/Login.php');

class Register extends \login\model\Login {
	/**
	 * Array of, if any, observers.
	 * @var \register\model\Observer
	 */
	private $observers;

	/**
	 * Run LoginModel constructor to make sure
	 * we have all objects initiated.
	 */
	public function __construct() {
		//Set observers as array.
		$this->observers = array();

		//Run the parent constructor.
		parent::__construct();
	}

	/**
	 * Notify all registered observers that the user
	 * registration was successful.
	 * @return void
	 */
	private function notifyObservers() {
		//Make sure we have any observers.
		if(count($this->observers) > 0) {
			//Loop through them.
			foreach($this->observers as $observer) {
				//Call method.
				$observer->notify();
			}
		}
	}

	/**
	 * In order to observe when a registration fails or
	 * when a registration is successful one has to register
	 * to this class with this method.
	 * @param  \register\model\Observer $observer
	 * @return void
	 */
	public function registerObserver(\register\model\Observer $observer) {
		//Add to our observer array.
		$this->observers[] = $observer;
	}

	/**
	 * By using this following method one can unsubscribe
	 * to this class.
	 * @param  \register\model\Observer $observer
	 * @return void
	 */
	public function unregisterObserver(\register\model\Observer $observer) {
		//Loop through all observers.
		foreach($this->observers as $key => $obs) {
			//If current iteration is the same as
			//the one we want to unregister.
			if($obs == $observer) {
				//Remove it.
				unset($this->observers[$key]);
			}
		}
	}

	/**
	 * Given a User object this method asks
	 * our UserList if the username is available and
	 * if it is then we proceed to add it to the list
	 * along with the chosen password of course.
	 * @param  \user\model\user $user
	 * @return void
	 */
	public function registerUser(\user\model\User $user) {
		//Is the username available?
		if(!$this->userList->isUsernameAvailable($user))
			throw new \Exception('Register::registerUser() failed: username is already taken');

		//Add our new user.
		$this->userList->addUser($user);

		//Notify observers.
		$this->notifyObservers();
	}
}