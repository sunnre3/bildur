<?php

namespace database\model;

require_once("./database/model/Base.php");
require_once("./user/model/Credentials.php");

class Setup extends Base {
	private static $DEFAULT_ADMIN_USERNAME = "admin";
	private static $DEFAULT_ADMIN_PASSWORD = "admin";

	public function clear() {
		$this->executeQuery("drop table post;");
		$this->executeQuery("drop table image;");
		$this->executeQuery("drop table user;");
	}

	/**
	 * To avoid misstakes this will be the method
	 * to be run if we want to setup our database.
	 * @return void
	 */
	public function runSetup() {
		//Make sure that we aren't set up yet.
		assert(!$this->isSetUp());

		//Set up the database tables.
		$this->addTables();

		//Add default values (admin etc).
		$this->addDefaults();

		//Add example posts
		$this->addExamplePosts();
	}

	/**
	 * Creates all the necessary tables and also
	 * some example posts for first time use.
	 * @return void
	 */
	private function addTables() {
		try {
			//Add our post table.
			$this->executeQuery( "
				CREATE TABLE IF NOT EXISTS " . parent::$POST_TABLE_NAME . "
				(
					id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
					user_id int NOT NULL, 
					title varchar(50) NOT NULL,
					datetime datetime NOT NULL,
					content text
				)
			");
			
			//Add our image table.
			$this->executeQuery("
				CREATE TABLE IF NOT EXISTS " . parent::$IMAGE_TABLE_NAME . "
				(
					id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
					post_id int NOT NULL,
					filepath varchar(100) NOT NULL,
					title varchar(100) NOT NULL,
					caption varchar(300)
				)
			");

			//Add our user table.
			$this->executeQuery("
				CREATE TABLE IF NOT EXISTS " . parent::$USER_TABLE_NAME . "
				(
					id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
					username varchar(20) NOT NULL,
					password varchar(255) NOT NULL
				)
			");
		}

		catch(\Exception $e) {
			throw $e;
		}
	}

	/**
	 * We want to add some default values to our database.
	 * @return void
	 */
	private function addDefaults() {
		try {
			//Get username and password for admin
			//NOTE: This is where we might get password from front-end installer ui.

			//Initiate UserCredentials object.
			$uc = \user\model\Credentials::fromText(
				self::$DEFAULT_ADMIN_USERNAME,
				self::$DEFAULT_ADMIN_PASSWORD);

			//Add admin to user table.
			$this->executeQuery("
				INSERT INTO " . parent::$USER_TABLE_NAME . " (username, password)
				VALUES ('" . $uc->getUsername() . "','" . $uc->getPassword() . "')
			");
		}

		catch(\Exception $e) {
			throw $e;
		}
	}

	/**
	 * When we install the application first we will want
	 * some example posts to see how everything works.
	 * @return void
	 */
	private function addExamplePosts() {
		try {
			$adminID = 1;
			date_default_timezone_set("Europe/Stockholm");
			$time = date("Y-m-d H:i:s");

			$this->executeQuery("
				INSERT INTO post (user_id, title, datetime, content)
				VALUES ('".$adminID."','Hello World!','".$time."','Det här är ett testinlägg! Antingen redigera detta eller så tar du bort det.')
			");

			 $this->executeQuery("
			 	INSERT into image (post_id, title, caption)
			 	VALUES (1, 'En bildtitel', 'En bildtext')
			 ");
		}

		catch(\Exception $e) {
			throw $e;
		}
	}
}