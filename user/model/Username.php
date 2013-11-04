<?php

namespace user\model;

require_once('./common/Filter.php');

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
			throw new \Exception('Username::__construct() failed: Invalid username');

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
	 * Verifies a username.
	 * Returns true if it maches,
	 * false if it doesn't.
	 * @param  \user\model\username $username
	 * @return boolean
	 */
	public function verify(\user\model\Username $other) {
		return strtolower($this->username) == strtolower($other->username);
	}

	/**
	 * @param  string $username
	 * @return boolean
	 */
	private function validate($username) {
		return !strlen($username) < self::MIN_LENGTH || strlen($username) > self::MAX_LENGTH || \common\Filter::hasTags($username);
	}
}