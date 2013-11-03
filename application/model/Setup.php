<?php

namespace application\model;

require_once('./database/model/Setup.php');

class Setup {
	/**
	 * @var \database\model\dbBase;
	 */
	private $dbSetup;

	/**
	 * Initiates objects
	 */
	public function __construct() {
		$this->dbSetup = new \database\model\Setup();
	}

	/**
	 * Asks database if tables exists etc
	 * @return boolean
	 */
	public function isSetUp() {
		return $this->dbSetup->isSetUp();
	}

	/**
	 * Run setup
	 * @return void
	 */
	public function setup() {
		$this->dbSetup->runSetup();
	}

	/**
	 * ONLY FOR DEVELOPING PURPOSES!
	 * REMEMBER TO REMOVE BEFORE RELEASE!
	 * @return void
	 */
	public function clearDB() {
		$this->dbSetup->clear();
	}
}