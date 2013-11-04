<?php

namespace database\model;

require_once('./database/model/Base.php');
require_once('./user/model/User.php');

class Setup extends Base {
	private static $DEFAULT_ADMIN_USERNAME = 'admin';
	private static $DEFAULT_ADMIN_PASSWORD = 'admin';
	private static $DEFAULT_ADMIN_EMAIL = 'sunnre3@gmail.com';

	public function clear() {
		$this->executeQuery('drop database bildur;');
		parent::__construct();
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
		$admin = $this->addDefaults();

		//Add example posts
		$this->addExamplePosts($admin);
	}

	/**
	 * Creates all the necessary tables and also
	 * some example posts for first time use.
	 * @return void
	 */
	private function addTables() {
		try {
			//Add our user table.
			$this->executeQuery("
				CREATE TABLE IF NOT EXISTS " . parent::$USER_TABLE_NAME . "
				(
					id int unsigned NOT NULL AUTO_INCREMENT,
					username varchar(20) NOT NULL,
					password varchar(255) NOT NULL,
					temp_password varchar(255) NOT NULL,
					email varchar(100) NOT NULL,
					PRIMARY KEY (id)
				)
			");

			//Add our post table.
			$this->executeQuery( "
				CREATE TABLE IF NOT EXISTS " . parent::$POST_TABLE_NAME . "
				(
					id int unsigned NOT NULL AUTO_INCREMENT,
					user_id int unsigned NOT NULL, 
					title varchar(50) NOT NULL,
					datetime datetime NOT NULL,
					content text,
					PRIMARY KEY (id),
					FOREIGN KEY (user_id) REFERENCES user(id)
						ON UPDATE CASCADE
						ON DELETE CASCADE
				)
			");
			
			//Add our image table.
			$this->executeQuery("
				CREATE TABLE IF NOT EXISTS " . parent::$IMAGE_TABLE_NAME . "
				(
					id int unsigned NOT NULL AUTO_INCREMENT,
					post_id int unsigned NOT NULL,
					filepath varchar(100) NOT NULL,
					thumbnail_filepath varchar(100) NOT NULL,
					PRIMARY KEY (id),
					FOREIGN KEY (post_id) REFERENCES post (id)
						ON UPDATE CASCADE
						ON DELETE CASCADE

				)
			");

			//Add our comment table.
			$this->executeQuery("
				CREATE TABLE IF NOT EXISTS " . parent::$COMMENT_TABLE_NAME . "
				(
					id int unsigned NOT NULL AUTO_INCREMENT,
					post_id int unsigned NOT NULL,
					user_id int unsigned NOT NULL,
					datetime datetime NOT NULL,
					content text NOT NULL,
					PRIMARY KEY (id),
					FOREIGN KEY (post_id) REFERENCES post (id)
						ON UPDATE CASCADE
						ON DELETE CASCADE,
					FOREIGN KEY (user_id) REFERENCES user (id)
						ON UPDATE CASCADE
						ON DELETE CASCADE
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

			//Initiate User object.
			$user = \user\model\User::__new(
				self::$DEFAULT_ADMIN_USERNAME,
				self::$DEFAULT_ADMIN_EMAIL,
				self::$DEFAULT_ADMIN_PASSWORD);

			//Get DB connection.
			$mysqli = $this->getDBObject();

			//Add admin to user table.
			$this->executeQuery("
				INSERT INTO " . parent::$USER_TABLE_NAME . " (username, password, temp_password, email)
				VALUES ('{$user->getUsername()}','{$user->getPassword()}', '{$user->getTmpPassword()}', '{$user->getEmail()}')
			", $mysqli);

			//Set userID.
			$user->setId($mysqli->insert_id);

			//Return user.
			return $user;
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
	private function addExamplePosts(\user\model\User $user) {
		try {
			$adminID = $user->getId();
			date_default_timezone_set('Europe/Stockholm');
			$time = date('Y-m-d H:i:s');

			//Create 20 example posts for development purpose
			for($i = 1; $i < 21; $i++) {
				//Get the date (although unnecessary as we don't store microseconds).
				$time = date("Y-m-d H:i:s");

				//Add the post.
				$this->executeQuery("
					INSERT INTO " . parent::$POST_TABLE_NAME . " (user_id, title, datetime, content)
					VALUES ({$adminID},'Hello World!','{$time}','Det här är ett testinlägg! Antingen redigera detta eller så tar du bort det.')
				");

				//Then one image attached to it.
				 $this->executeQuery("
				 	INSERT into " . parent::$IMAGE_TABLE_NAME . " (post_id, filepath, thumbnail_filepath)
				 	VALUES ({$i}, '" . UPLOAD_PATH . "exempelbild.png', '" . UPLOAD_PATH . "exempelbild.png')
				 ");

				 //And add one comment to each.
				 $this->executeQuery("
				 	INSERT into " . parent::$COMMENT_TABLE_NAME . " (post_id, user_id, datetime, content)
				 	VALUES ({$i}, {$adminID}, '{$time}', 'En testkommentar!')
				 ");
			}
		}

		catch(\Exception $e) {
			throw $e;
		}
	}
}