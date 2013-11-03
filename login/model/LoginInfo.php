<?php

namespace login\model;

class LoginInfo {
	/**
	 * @var \user\model\User
	 */
	public $user;

	/**
	 * @var string
	 */
	private $ip;

	/**
	 * @var string
	 */
	private $userAgent;

	/**
	 * Save a User object along with some
	 * other data that belongs to the person
	 * who logged in to try and make it safe.
	 * @param \user\model\User $user
	 */
	public function __construct(\user\model\User $user) {
		$this->user = $user;
		$this->ip = $_SERVER['REMOTE_ADDR'];
		$this->userAgent = $_SERVER['HTTP_USER_AGENT'];
	}

	/**
	 * Compares the info with the server to make
	 * sure that it's the  same person trying to login.
	 * @return boolean
	 */
	public function compareInfo() {
		return $this->ip == $_SERVER['REMOTE_ADDR'] && $this->userAgent == $_SERVER['HTTP_USER_AGENT'];
	}
}