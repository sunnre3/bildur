<?php

namespace user\model;

require_once('./user/model/Username.php');
require_once('./user/model/Password.php');
require_once('./user/model/TemporaryPassword.php');

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
	 * @var \user\model\TemporaryPassword
	 */
	private $temporaryPassword;

	/**
	 * @var string
	 */
	private $email;

	/**
	 * Because you can't overload in PHP this
	 * is one solution. This static method
	 * takes a username in cleartext but an
	 * encrypted password and returns an 
	 * instance of User.
	 * @param int $id
	 * @param string $username
	 * @param string $password encrypted
	 */
	public static function __enc($id, $username, $encrypted_password, $temporary_password, $email) {
		//Create an instance.
		$instance = new self();

		//Set the ID.
		$instance->id = $id;

		//Set the username.
		$instance->username = new \user\model\Username($username);

		//Set the password.
		$instance->password = \user\model\Password::__enc($encrypted_password);

		//Set the temporary password.
		$instance->temporaryPassword = \user\model\TemporaryPassword::__enc($temporary_password);

		//Set the email.
		$instance->email = $email;

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
	public static function __login($username, $password) {
		//Create an instance.
		$instance = new self();

		//Set the username.
		$instance->username = new \user\model\Username($username);

		//Set the password.
		$instance->password = \user\model\Password::__new($password);

		//Create a new temporary password.
		$instance->temporaryPassword = \user\model\TemporaryPassword::__new();

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
	public static function __saved($username, $password) {
		//Create an instance.
		$instance = new self();

		//Set the username.
		$instance->username = new \user\model\Username($username);

		//Set the password.
		$instance->password = new \user\model\Password();

		//Create a new temporary password.
		$instance->temporaryPassword = \user\model\TemporaryPassword::__enc($password);

		//Return the instance.
		return $instance;
	}

	/**
	 * Because you can't overload in PHP this
	 * is one solution. This static function
	 * is used to return a User object when the
	 * user wants to register.
	 * @param  string $username
	 * @param  string $password
	 * @return \user\model\User
	 */
	public static function __new($username, $email, $password, $verified = "") {
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
		$instance->password = \user\model\Password::__new($password);

		//Create a temporary password.
		$instance->temporaryPassword = \user\model\TemporaryPassword::__new();

		//Set the email.
		$instance->email = $email;

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

	/**
	 * Returns string representation of the
	 * temporary password.
	 * @return string
	 */
	public function getTmpPassword() {
		return $this->temporaryPassword->__toString();
	}

	/**
	 * Get method for private property $email.
	 * @return string email
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * Set method for private property $email.
	 * @param string $value email
	 */
	public function setEmail($value) {
		$this->email = $value;
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
			   && ($this->password->verify($other->password)
			   	   || $this->temporaryPassword->verify($other->temporaryPassword));
	}
}