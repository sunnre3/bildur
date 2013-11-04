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
		return \user\model\User::__enc($obj['id'],
									   $obj['username'],
									   $obj['password'],
									   $obj['temp_password'],
									   $obj['email']);
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
			$users[] = \user\model\User::__enc($obj['id'],
											   $obj['username'],
											   $obj['password'],
									   		   $obj['temp_password'],
											   $obj['email']);
		}
		
		//Return the array.
		return $users;
	}

	/**
	 * With queries and a given User object we
	 * can with this method add a new to our database.
	 * @param  \user\model\User $user
	 * @return \user\model\User
	 */
	public function addUser(\user\model\User $user) {
		//Get a db connection.
		$db = $this->getDBObject();

		//MySQL query.
		$query  = "INSERT INTO " . parent::$USER_TABLE_NAME . " (username, password, temp_password, email) 
		VALUES('{$user->getUsername()}', '{$user->getPassword()}', '{$user->getTmpPassword()}', '{$user->getEmail()}')";

		//Execute query.
		$result = $this->executeQuery($query, $db);

		//Set the userId.
		$user->setId($db->insert_id);

		//Return the user.
		return $user;
	}

	/**
	 * With this method we can update a
	 * user in our database.
	 * @param  \user\model\User $user
	 * @return void
	 */
	public function updateUser(\user\model\User $user) {
		//MySQL query.
		$query = "UPDATE " . parent::$USER_TABLE_NAME .
		" SET temp_password='{$user->getTmpPassword()}'
		 WHERE id={$user->getId()}";

		//Execute query.
		$result = $this->executeQuery($query);
	}
}