<?php

namespace user\model;

require_once('./user/model/Username.php');
require_once('./user/model/Password.php');

class User {
	/**
	 * @var int
	 */
	private $id;

	/**
	 * @var \user\model\Username
	 */
	private $username;

	/**
	 * @var \user\model\Password
	 */
	private $password;

	/**
	 * SEMI overloaded constructor that takes a username
	 * in cleartext but an encrypted password and returns
	 * an instance of User.
	 * @param int $id
	 * @param string $username
	 * @param string $password encrypted
	 */
	public static function encrypted($id, $username, $encrypted_password) {
		//Create an instance.
		$instance = new self();

		//Set the ID.
		$instance->id = $id;

		//Set the username.
		$instance->username = new \user\model\Username($username);

		//Set the password.
		$instance->password = \user\model\Password::encrypted($encrypted_password);

		//Return the instance.
		return $instance;
	}

	/**
	 * Because you can't overload in PHP this
	 * is one solution. This static function
	 * takes a cleartext username and password
	 * and returns an instance of User.
	 * @param  string $username
	 * @param  string $password
	 * @return \user\model\User
	 */
	public static function cleartext($username, $password, $verified = "") {
		if($verified == "")
			$verified = $password;

		//This method runs from user input and
		//a user has to provide the password twice.
		if($password != $verified)
			throw new \Exception('Credentials::fromText() failed: passwords do not match');

		//Create an instance.
		$instance = new self();

		//Set the username.
		$instance->username = new \user\model\Username($username);

		//Set the password.
		$instance->password = \user\model\Password::cleartext($password);

		//Return the instance.
		return $instance;
	}

	/**
	 * Get method for private property $id.
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set method for private property $id.
	 * @param int $value
	 */
	public function setId($value) {
		if(!is_numeric($value))
			throw new \Exception('User::setId() failed: value is not numeric');

		$this->id = $value;
	}

	/**
	 * Returns string representation of username.
	 * @return string
	 */
	public function getUsername() {
		return $this->username->__toString();
	}

	/**
	 * Returns string representation of password.
	 * @return string
	 */
	public function getPassword() {
		return $this->password->__toString();
	}

	public function getClearPassword() {
		return $this->password->cleartext;
	}

	/**
	 * Compare usernames.
	 * Returns true if they are the same,
	 * or false if they aren't.
	 * @param  \user\model\User $other
	 * @return boolan
	 */
	public function compareUsername(\user\model\User $other) {
		return strtolower($this->getUsername()) == strtolower($other->getUsername());
	}

	/**
	 * Compares one User object to another.
	 * Returns true if they are they the the same,
	 * returns false if they aren't.
	 * @param  \user\model\User $other
	 * @return boolean
	 */
	public function compareUser(\user\model\User $other) {
		return $this->username->verify($other->username)
			   && $this->password->verify($other->password);
	}
}