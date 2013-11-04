<?php

namespace login\model;

require_once('./user/model/User.php');
require_once('./user/model/UserList.php');
require_once('./login/model/LoginInfo.php');
require_once('./common/model/Observer.php');

class Login implements \common\model\Observer {
	private static $LOGGED_IN_USER = 'Login::LoggedInUser';

	/**
	 * Array of, if any, observers.
	 * @var \register\model\Observer
	 */
	private $observers;

	/**
	 * This class will handle adding our user
	 * to the user registry and we can also
	 * ask this class if the username is available etc.
	 * @var \user\model\UserList
	 */
	protected $userList;

	/**
	 * Initiates our UserDAL object.
	 */
	public function __construct() {
		//Initiate UserList.
		$this->userList = new \user\model\UserList();
	}

	/**
	 * Notify all registered observers that the user
	 * registration was successful.
	 * @return void
	 */
	private function notifyObservers(\user\model\User $user) {
		//Make sure we have any observers.
		if(count($this->observers) > 0) {
			//Loop through them.
			foreach($this->observers as $observer) {
				//Call method.
				$observer->notify($user);
			}
		}
	}

	/**
	 * In order to observe when a registration fails or
	 * when a registration is successful one has to register
	 * to this class with this method.
	 * @param  \login\model\Observer $observer
	 * @return void
	 */
	public function registerObserver(\common\model\Observer $observer) {
		//Add to our observer array.
		$this->observers[] = $observer;
	}

	/**
	 * By using this following method one can unsubscribe
	 * to this class.
	 * @param  \login\model\Observer $observer
	 * @return void
	 */
	public function unregisterObserver(\common\model\Observer $observer) {
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
	 * Attempts to login a user when given a User objects.
	 * We try and compare our given User with the sets of User
	 * objects in the UserList.
	 * @param  \user\model\User $user
	 * @return void
	 */
	public function doLogin(\user\model\User $user) {
		//Make sure the user is valid.
		if($this->userList->validateUser($user)) {
			//Set the correct id.
			$user = $this->userList->findUserID($user);

			//Login the user.
			$this->setLoggedIn($user);

			//Update the user in our database with
			//the new temporary password.
			$this->userList->updateUser($user);

			//Notify observers.
			$this->notifyObservers($user);
		}

		else {
			throw new \Exception('Login::doLogin() failed: invalid login');
		}
	}

	/**
	 * Unsets the session to log out the user.
	 * @return void
	 */
	public function doLogout() {
		unset($_SESSION[self::$LOGGED_IN_USER]);
	}

	/**
	 * Publicly function to see if the user is logged in.
	 * @return boolean
	 */
	public static function isLoggedIn() {
		if(isset($_SESSION[self::$LOGGED_IN_USER])) {
			if($_SESSION[self::$LOGGED_IN_USER]->compareInfo())
				return true;
		}

		return false;
	}

	/**
	 * Returns the User object for the logged in user.
	 * @return \user\model\User
	 */
	public function getLoggedInUser() {
		return $_SESSION[self::$LOGGED_IN_USER]->user;
	}

	/**
	 * Logs in a user.
	 * @param \user\model\User $user
	 */
	private function setLoggedIn(\user\model\User $user) {
		$_SESSION[self::$LOGGED_IN_USER] = new \login\model\LoginInfo($user);
	}

	/**
	 * From RegisterObserver interface.
	 * When a registration has been successful
	 * we want to the user who just registrated
	 * to be logged in automatically.
	 * @param  \user\model\User $user
	 * @return void
	 */
	public function notify(\user\model\User $user) {
		$this->setLoggedIn($user);
	}
}