<?php

namespace user\model;

require_once('./common/Filter.php');

class Password {
	const MIN_LENGTH = 5;
	const MAX_LENGTH = 30;

	/**
	 * Representation of the original
	 * password but encrypted.
	 * @var string
	 */
	private $password;

	/**
	 * Representation of the password
	 * in a non-encrypted string.
	 * @var string
	 */
	private $cleartext;

	/**
	 * This constructor takes an encrypted password.
	 * @param string $encrypted_password
	 */
	public static function __enc($encrypted_password) {
		//Create an instance.
		$instance = new self();

		//Set the password.
		$instance->password = $encrypted_password;

		//Return the instance.
		return $instance;
	}

	/**
	 * Creates an instance of Password from a password
	 * presented in cleartext.
	 * @param  string $password cleartext
	 * @return \user\model\Password
	 */
	public static function __new($password) {
		//If the password isn't valid, throw.
		if(!self::validate($password))
			throw new \Exception('Password::fromText() failed: invalid password');

		//Create an instance.
		$instance = new self();

		//Set the password.
		$instance->cleartext = $password;

		//Set the encrypted password.
		$instance->password = password_hash($password, PASSWORD_BCRYPT);

		//Return the instance.
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
	 * Verifies a cleartext password to our hash.
	 * Returns true if it maches,
	 * false if it doesn't.
	 * @param  \user\model\Password $password
	 * @return boolean
	 */
	public function verify(\user\model\Password $other) {
		return password_verify($other->cleartext, $this->password);
	}

	/**
	 * @param  string $password
	 * @return boolean
	 */
	private static function validate($password) {
		return !strlen($password) < self::MIN_LENGTH || strlen($password) > self::MAX_LENGTH || \common\Filter::hasTags($password);
	}
}