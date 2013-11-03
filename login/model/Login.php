<?php

namespace login\model;

require_once('./user/model/User.php');
require_once('./login/model/LoginInfo.php');

class Login {
	private static $LOGGED_IN_USER = 'Login::LoggedInUser';

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
	public function isLoggedIn() {
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
}