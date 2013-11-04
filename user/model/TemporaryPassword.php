<?php

namespace user\model;

class TemporaryPassword {
	/**
	 * A temporary password that can be stored
	 * at client.
	 * @var string
	 */
	public $password;

	/**
	 * UNIX timestamp representing
	 * when the temporary password expires.
	 * @var int
	 */
	public $expireDate;

	/**
	 * Alternative constructor that can be
	 * used when parsing a temporary password
	 * stored in the system.
	 * @param  string $string
	 * @return \user\model\TemporaryPassword
	 */
	public static function __enc($string) {
		//Split the string to get the values.
		list($password, $expireDate) = explode(":", $string);

		//Create an instance.
		$instance = new self();

		//Set password.
		$instance->password = $password;

		//Set the date.
		$instance->expireDate = $expireDate;

		//Return the instance.
		return $instance;
	}

	/**
	 * Alternative constructor that can be used
	 * when you need a new temporary password.
	 * @return \user\model\TemporaryPassword
	 */
	public static function __new() {
		//Create an instance.
		$instance = new self();

		//Set a password.
		$instance->password = md5(rand(0, time()));

		//Set a date.
		$instance->expireDate = time() + 3600; //1 hour

		//Return the instance.
		return $instance;
	}

	/**
	 * Returns the temporary password as a string
	 * so you can save it in the system.
	 * @return string
	 */
	public function __toString() {
		return $this->password . ':' . $this->expireDate;
	}

	public function verify(\user\model\TemporaryPassword $other) {
		return $this->password == $other->password && $other->expireDate > time();
	}
}