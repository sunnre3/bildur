<?php

namespace user\model;

require_once('./database/model/Base.php');
require_once('./user/model/User.php');

class UserDAL extends \database\model\Base {
	public function getUser($id) {
		//MySQL query.
		$query = 'SELECT * FROM ' . parent::$USER_TABLE_NAME . ' WHERE id=' . $id;

		//Result.
		$result = $this->executeQuery($query);

		//Get object.
		$obj = $result->fetch_array(MYSQLI_ASSOC);

		//Return.
		return \user\model\User::encrypted($obj['id'],
										   $obj['username'],
										   $obj['password']);
	}

	/**
	 * Queries the database and retrieves all
	 * our current registrated users.
	 * @return \user\model\User[]
	 */
	public function getUsers() {
		//Create an array.
		$users = array();

		//MySQL query.
		$query = 'SELECT * FROM ' . parent::$USER_TABLE_NAME;

		//Set of result.
		$result = $this->executeQuery($query);

		//Loop through result to create objects.
		while($obj = $result->fetch_array(MYSQLI_ASSOC)) {
			$users[] = \user\model\User::encrypted($obj['id'],
												   $obj['username'],
												   $obj['password']);
		}
		
		//Return the array.
		return $users;
	}

	/**
	 * With queries and a given User object we
	 * can with this method add a new to our database.
	 * @param  \user\model\User $user
	 * @return void
	 */
	public function addUser(\user\model\User $user) {
		//MySQL query.
		$query  = 'INSERT INTO ' . parent::$USER_TABLE_NAME . ' (username, password) 
		VALUES(\'' . $user->getUsername() . '\', \'' . $user->getPassword() . '\')';

		//Execute query.
		$result = $this->executeQuery($query);
	}
}