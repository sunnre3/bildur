<?php

namespace user\model;

class Username {
	const MIN_LENGTH = 3;
	const MAX_LENGTH = 20;

	/**
	 * Valid username
	 * @var string
	 */
	private $username;

	/**
	 * Creates a username if it's valid.
	 * @param string $_username
	 */
	public function __construct($_username) {
		//First validate the username.
		if(!$this->validate($_username))
			throw new \Exception("Username::__construct() failed: Invalid username");

		//If it's a valid username then set it.
		$this->username = $_username;
	}

	/**
	 * Returns a string representation of the username
	 * @return string
	 */
	public function __toString() {
		return $this->username;
	}

	/**
	 * @param  string $username
	 * @return boolean
	 */
	private function validate($username) {
		return !(strlen($username) < self::MIN_LENGTH || strlen($username) > self::MAX_LENGTH);
	}
}