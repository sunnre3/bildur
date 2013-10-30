<?php

namespace user\model;

require_once("./user/model/Username.php");
require_once("./user/model/Password.php");

class Credentials {
	/**
	 * @var \user\model\Username
	 */
	private $username;

	/**
	 * @var \user\model\Password
	 */
	private $password;

	/**
	 * Because you can't overload in PHP this
	 * is one solution. This static function
	 * takes a cleartext username and password
	 * and returns an instance of Credentials.
	 * @param  string $_username
	 * @param  string $_password
	 * @return \user\model\Credentials
	 */
	public static function fromText($_username, $_password) {
		$instance = new self();
		$instance->username = new \user\model\Username($_username);
		$instance->password = \user\model\Password::fromText($_password);
		return $instance;
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
}