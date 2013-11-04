<?php

namespace image\model;

class Image {
	/**
	 * Private representation of all properties of an Image object.
	 * This will get extended with getters and setters.
	 */
	private $id, $post_id, $filepath, $thumbnail_filepath;

	/**
	 * Constructor to create an Image object
	 * with all correct and necessary properties.
	 * @param int    $id
	 * @param int    $post_id
	 * @param string $filepath
	 * @param string $title
	 * @param string $caption
	 */
	public function __construct($id, $post_id, $filepath, $thumbnail_filepath) {
		$this->setId($id);
		$this->setPostId($post_id);
		$this->setFilePath($filepath);
		$this->setThumbnail($thumbnail_filepath);
	}

	/**
	 * Alternative constructor for when you
	 * can't supply $id and $post_id.
	 * @param  string $filepath
	 * @param  string $thumbnail_filepath
	 * @return \image\model\Image
	 */
	public static function __new($filepath, $thumbnail_filepath) {
		//Create an instance.
		$instance = new self(0, 0, $filepath, $thumbnail_filepath);

		//Return instance.
		return $instance;
	}

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
			throw new \Exception('Image::setId() failed: value is not numeric');

		$this->id = $value;
	}

	/**
	 * Get method for property $post_id
	 * @return int
	 */
	public function getPostId() {
		return $this->post_id;
	}

	/**
	 * Set method for property $post_id
	 * @param int $value
	 */
	public function setPostId($value) {
		if(!is_numeric($value))
			throw new \Exception('Image::setPostId() failed: value is not numeric');

		$this->post_id = $value;
	}

	/**
	 * Get method for property $filepath
	 * @return string
	 */
	public function getFilePath() {
		return $this->filepath;
	}

	/**
	 * Set method for property $filepath
	 * @param string $value
	 */
	public function setFilePath($value) {
		if(strlen($value) > 100)
			throw new \Exception('Image::setFilePath() failed: value is too long');

		$this->filepath = $value;
	}

	/**
	 * Get method for property $thumbnail_filepath
	 * @return int
	 */
	public function getThumbnail() {
		return $this->thumbnail_filepath;
	}

	/**
	 * Set method for property $thumbnail_filepath
	 * @param int $value
	 */
	public function setThumbnail($value) {
		if(strlen($value) > 100)
			throw new \Exception('Image::setThumbnail() failed: value is too long');

		$this->thumbnail_filepath = $value;
	}
}