<?php

namespace database\model;

require_once('./database/config.php');

abstract class Base {
	protected static $POST_TABLE_NAME = 'post';
	protected static $USER_TABLE_NAME = 'user';
	protected static $IMAGE_TABLE_NAME = 'image';
	protected static $COMMENT_TABLE_NAME = 'comment';

	private static $DB_CONNECT_ERROR = 'Error connecting to database';
	private static $DB_CREATE_DATABASE_ERROR = 'Error creating database';

	public function __construct() {
		//Make sure that the database exists
		//before anything else.
		try {
			$db = new \MySQLi(DB_HOST, DB_USER, DB_PW);
			$query = 'CREATE DATABASE IF NOT EXISTS ' . DB_NAME;
			$this->executeQuery($query, $db);
		}
		catch(\Exception $e) {
			throw new \Exception(self::$DB_CREATE_DATABASE_ERROR . $db->connect_error);
		}
	}

	public function isSetUp() {
		//Get a db connection.
		$db = $this->getDBObject();

		if(!$db) {
			$db->close();
			return false;
		}

		elseif(!$this->table_exists(self::$USER_TABLE_NAME)) {
			$db->close();
			return false;
		}

		elseif(!$this->table_exists(self::$POST_TABLE_NAME)) {
			$db->close();
			return false;
		}

		elseif(!$this->table_exists(self::$IMAGE_TABLE_NAME)) {
			$db->close();
			return false;
		}

		elseif(!$this->table_exists(self::$COMMENT_TABLE_NAME)) {
			$db->close();
			return false;
		}

		else {
			$db->close();
			return true;
		}
	}

	/**
	 * Returns a mysqli object
	 * @return \MySQLi
	 */
	protected function getDBObject() {
		try {
			$mysqli = new \MySQLi(DB_HOST, DB_USER, DB_PW, DB_NAME);
			return $mysqli;
		}

		catch(\Exception $e) {
			throw new \Exception(self::$DB_CONNECT_ERROR);
		}
	}

	/**
	 * Executes a query and returns the resultset.
	 * @param  string  $query MySQL query
	 * @param  \MySQLi $conn
	 * @return MyMSQLi resultset
	 */
	protected function executeQuery($query, \MySQLi $conn = null) {
		//If no $conn is provided, create a new one.
		if($conn == null)
			$conn = $this->getDBObject();

		//Execute query.
		$result = $conn->query($query);

		//If the query fails we need to throw.
		if(!$result)
			throw new \Exception("{$conn->error} Full query: [{$query}");

		//Return result.
		return $result;
	}

	/**
	 * Check if a table exists within our database
	 * @param  string $table table name
	 * @return boolean
	 */
	protected function table_exists($table) {
		$result = $this->executeQuery("SHOW TABLES LIKE '{$table}'");

		if(!$result) {
			return false;
		}

		else {
			return $result->num_rows == 1 ? true : false;
		}
	}
}