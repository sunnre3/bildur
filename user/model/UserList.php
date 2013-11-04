<?php

namespace user\model;

require_once('./user/model/UserDAL.php');

class UserList {
	/**
	 * DAL class to save, edit and
	 * retrieve our users from the database.
	 * @var \user\model\UserDAL
	 */
	private $userDAL;

	/**
	 * Array containing all registrated users.
	 * @var \user\model\User[]
	 */
	private $users;

	/**
	 * Initiates our DAL object and populates
	 * the users array with all found users.
	 */
	public function __construct() {
		//Get a new UserDAL object.
		$this->userDAL = new \user\model\UserDAL();

		//Get all users.
		$this->users = $this->userDAL->getUsers();
	}

	/**
	 * Checks every user in our array to try and find
	 * a match to our given user.
	 * @param  \user\model\User $other
	 * @return boolean
	 */
	public function validateUser(\user\model\User $other) {
		//Loop.
		foreach($this->users as $user) {
			//If we find a match....
			if($user->compareUser($other))
				return true;
		}

		//Else...
		return false;
	}

	/**
	 * Loop through our members to find and
	 * set the ID of the given User object.
	 * @param  \user\model\User $other
	 * @return \user\model\User
	 */
	public function findUserID(\user\model\User $other) {
		//Loop.
		foreach($this->users as $user) {
			//If we find a match....
			if($user->compareUser($other))
				$other->setId($user->getId());
		}

		return $other;
	}

	/**
	 * Loops through all the users in our array
	 * and checks if any of them has the same username.
	 * @param  \user\model\User $other
	 * @return boolean
	 */
	public function isUsernameAvailable(\user\model\User $new_user) {
		//Loop.
		foreach($this->users as $key => $user) {
			//Compare the given username with the
			//one we are currently working on in the loop.
			if($user->compareUsername($new_user))
				//If they are the same we return false.
				return false;
		}

		return true;
	}

	/**
	 * Adds a User to our system.
	 * @param  \user\model\User $new_user
	 * @return \user\model\User
	 */
	public function addUser(\user\model\User $new_user) {
		//Use our DAL to add the user to our database.
		$new_user = $this->userDAL->addUser($new_user);

		//Add the user to our array.
		$this->users[] = $new_user;

		//Return the user.
		return $new_user;
	}

	/**
	 * Updates a user in our system.
	 * @param  \user\model\User $user
	 * @return void
	 */
	public function updateUser(\user\model\User $user) {
		//Update with DAL.
		$updated_user = $this->userDAL->updateUser($user);

		//Get the key for the user in the array.
		$key = array_search($user, $this->users, true);

		//Replace the old with the new.
		$this->users[$key] = $updated_user;
	}
}