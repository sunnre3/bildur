<?php

namespace user\model;

class Password {
	const MIN_LENGTH = 3;
	const MAX_LENGTH = 30;

	/**
	 * Representation of the original
	 * password but encrypted.
	 * @var string
	 */
	private $password;

	/**
	 * Creates an instance of Password from a password
	 * presented in cleartext.
	 * @param  string $_password cleartext
	 * @return \user\model\Password
	 */
	public static function fromText($_password) {
		//If the password isn't valid, throw.
		if(!self::validate($_password))
			throw new \Exception("Password::fromText() failed: invalid password");

		$instance = new self();
		$instance->password = password_hash($_password, PASSWORD_BCRYPT);
		return $instance;
	}

	/**
	 * Returns a string representation of the encrypted password.
	 * @return string encrypted
	 */
	public function __toString() {
		return $this->password;
	}

	/**
	 * @param  string $password
	 * @return boolean
	 */
	private static function validate($password) {
		return !(strlen($password) < self::MIN_LENGTH || strlen($password) > self::MAX_LENGTH);
	}
}