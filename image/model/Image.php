<?php

namespace image\model;

class Image {
	/**
	 * Private representation of all properties of an Image object.
	 * This will get extended with getters and setters.
	 */
	private $id, $post_id, $filepath, $thumbnail_filepath, $title, $caption;

	/**
	 * Constructor to create an Image object
	 * with all correct and necessary properties.
	 * @param int    $id
	 * @param int    $post_id
	 * @param string $filepath
	 * @param string $title
	 * @param string $caption
	 */
	public function __construct($id, $post_id, $filepath, $thumbnail_filepath, $title, $caption) {
		$this->setId($id);
		$this->setPostId($post_id);
		$this->setFilePath($filepath);
		$this->setThumbnail($thumbnail_filepath);
		$this->setTitle($title);
		$this->setCaption($caption);
	}

	/**
	 * Alternative constructor for when you
	 * can't supply $id and $post_id.
	 * @param  [type] $filepath           [description]
	 * @param  [type] $thumbnail_filepath [description]
	 * @return [type]                     [description]
	 */
	public static function uploaded($filepath, $thumbnail_filepath) {
		//Create an instance.
		$instance = new self(0, 0, $filepath, $thumbnail_filepath, "", "");

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
		if(strlen($value) > 100)
			throw new \Exception('Image::setTitle() failed: value is too long');

		$this->title = $value;
	}

	/**
	 * Get method for property $caption
	 * @return string
	 */
	public function getCaption() {
		return $this->caption;
	}

	/**
	 * Set method for property $caption
	 * @param string $value
	 */
	public function setCaption($value) {
		if(strlen($value) > 300)
			throw new \Exception('Image::setCaption() failed: value is too long');

		$this->caption = $value;
	}
}