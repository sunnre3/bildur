<?php

namespace post\model;

class Post {
	/**
	 * Private representations of all properties of a post
	 * To be extended later with get/set.
	 */
	private $id, $user_id, $title, $datetime, $content;

	/**
	 * Get method for property $id
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set method for property $id
	 * @param int $value
	 */
	public function setId($value) {
		if(!is_numeric($value))
			throw new \Exception("Post::setId() failed: value is not numeric");

		$this->id = $value;
	}

	/**
	 * Get method for property $user_id
	 * @return int
	 */
	public function getUserId() {
		return $this->user_id;
	}

	/**
	 * Set method for property $user_id
	 * @param int $value
	 */
	public function setUserId($value) {
		if(!is_numeric($value))
			throw new \Exception("Post::setUserId() failed: value is not numeric");

		$this->user_id = $value;
	}

	/**
	 * Get method for property $title
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Set method for property $title
	 * @param string $value
	 */
	public function setTitle($value) {
		$value = trim($value);

		if(strlen($value) == 0)
			throw new \Exception("Post::setTitle() failed: value is empty");

		if(strlen($value) > 50)
			throw new \Exception("Post::setTitle() failed: value is too long");

		$this->title = $value;
	}

	/**
	 * Get method for property $datetime
	 * @return string datetime
	 */
	public function getDateTime() {
		return $this->datetime;
	}

	/**
	 * Set method for property $datetime
	 * @param string $value datetime
	 */
	public function setDateTime($value) {
		$this->datetime = $value;
	}

	/**
	 * Get method for getting $datetime in UNIX
	 * @return string UNIX time
	 */
	public function getUNIXTime() {
		$date = new \DateTime($this->datetime);
		return $date->format("U");
	}

	/**
	 * Get method for property $content
	 * @return string
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * Set method for property $content
	 * @param string $value
	 */
	public function setContent($value) {
		$this->content = $value;
	}

	/**
	 * Creates a post object
	 * @param int $id
	 * @param int $user_id
	 * @param string $title
	 * @param datetime $datetime
	 * @param string $content
	 */
	public function __construct($id, $user_id, $title, $datetime, $content) {
		$this->setId($id);
		$this->setUserId($user_id);
		$this->setTitle($title);
		$this->setDateTime($datetime);
		$this->setContent($content);
	}
}