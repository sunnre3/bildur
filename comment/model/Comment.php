<?php

namespace comment\model;

require_once('./common/Filter.php');

class Comment {
	/**
	 * Private representation of all of the comments
	 * properties. These will be completed will getter
	 * and setters.
	 */
	private $id, $post_id, $user_id, $datetime, $content;

	/**
	 * Creates a Coment object.
	 * @param int $id
	 * @param int $post_id
	 * @param int $user_id
	 * @param datetime $datetime
	 * @param string $content
	 */
	public function __construct($id, $post_id, $user_id, $datetime, $content) {
		$this->setId($id);
		$this->setPostId($post_id);
		$this->setUserId($user_id);
		$this->setDateTime($datetime);
		$this->setContent($content);
	}

	/**
	 * Alternative to the standard constructor for when you can't
	 * provide a commentId (e.g. when the user has just submitted a
	 * new comment) in the form of an publicly available static
	 * function.
	 * @param  int $post_id 
	 * @param  int $user_id
	 * @param  datetime $datetime
	 * @param  string $content
	 * @return \comment\model\Comment
	 */
	public static function __new($post_id, $user_id, $datetime, $content) {
		//Create an instance.
		$instance = new self(0, $post_id, $user_id, $datetime, $content);

		//Return instance.
		return $instance;
	}

	/**
	 * Get method for private property $id.
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set method for private property $id.
	 * @param int $value
	 */
	public function setId($value) {
		if(!is_numeric($value))
			throw new \Exception('Comment::setId() failed: value is not numeric');

		$this->id = $value;
	}

	/**
	 * Get method for private property $post_id.
	 * @return int
	 */
	public function getPostId() {
		return $this->post_id;
	}

	/**
	 * Set method for private property $post_id.
	 * @param int $value
	 */
	public function setPostId($value) {
		if(!is_numeric($value))
			throw new \Exception('Comment::setPostId() failed: value is not numeric');

		$this->post_id = $value;
	}

	/**
	 * Get method for private property $user_id.
	 * @return int
	 */
	public function getUserId() {
		return $this->user_id;
	}

	/**
	 * Set method for private property $user_id.
	 * @param int $value
	 */
	public function setUserId($value) {
		if(!is_numeric($value))
			throw new \Exception('Comment::setUserId() failed: value is not numeric');

		$this->user_id = $value;
	}

	/**
	 * Get method for private property $datetime.
	 * @return datetime
	 */
	public function getDateTime() {
		return $this->datetime;
	}

	/**
	 * Set method for private property $datetime.
	 * @param datetime $value
	 */
	public function setDateTime($value) {
		$this->datetime = $value;
	}

	/**
	 * Get method for private property $content.
	 * @return string
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * Set method for private property $content.
	 * @param string $value
	 */
	public function setContent($value) {
		if(strlen($value) < 1)
			throw new \Exception('Comment::setContent() failed: value must be longer than 0');

		$this->content = \common\Filter::sanitize($value);
	}
}