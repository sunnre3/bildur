<?php

namespace image\model;

require_once('./database/model/Base.php');
require_once('./image/model/Image.php');

class ImageDAL extends \database\model\Base {
	/**
	 * This method opens a database connection,
	 * takes an id from a given post and retrieves
	 * the thumbnail image.
	 * @param  \post\model\Post $post
	 * @return \image\model\Image
	 */
	public function getThumbnail(\post\model\Post $post) {
		try {
			//MySQL query.
			$query = "SELECT * FROM " . parent::$IMAGE_TABLE_NAME . " WHERE post_id = {$post->getId()}";

			//Result.
			$result = $this->executeQuery($query);

			//Lets work with the first image found.
			$obj = $result->fetch_array(MYSQLI_ASSOC);

			//Return an image object.
			return new \image\model\Image($obj['id'],
										  $obj['post_id'],
										  $obj['filepath'],
										  $obj['thumbnail_filepath'],
										  $obj['title'],
										  $obj['caption']);
		}

		catch(\Exception $e) {
			throw $e;
		}
	}

	/**
	 * Returns all images associated with the
	 * given post.
	 * @param  \post\model\Post $post
	 * @return \image\model\Image[]
	 */
	public function getImages(\post\model\Post $post) {
		try {
			//Create an array.
			$images = array();

			//MySQL query.
			$query = "SELECT * FROM " . parent::$IMAGE_TABLE_NAME . " WHERE post_id = {$post->getId()}";

			//Result.
			$result = $this->executeQuery($query);

			//Loop through result.
			while($obj = $result->fetch_array(MYSQLI_ASSOC)) {
				//Create new Image objects and add them to the return array.
				$images[] = new \image\model\Image($obj['id'],
												   $obj['post_id'],
												   $obj['filepath'],
												   $obj['thumbnail_filepath'],
												   $obj['title'],
												   $obj['caption']);
			
			}

			//Return the array.
			return $images;
		}

		catch(\Exception $e) {
			throw $e;
		}
	}

	/**
	 * Saves an Image object to our database.
	 * @param \image\model\Image $image
	 * @return void
	 */
	public function addImage(\image\model\Image $image) {
		//MySQL query.
		$query = 'INSERT INTO ' . parent::$IMAGE_TABLE_NAME . ' (post_id, filepath, thumbnail_filepath, title, caption) 
		VALUES(\'' . $image->getPostId() . '\', \'' . $image->getFilePath() . '\', \'' . $image->getThumbnail() . '\', \'' . $image->getTitle() . '\', \'' . $image->getCaption() . '\')';

		//Execute.
		$result = $this->executeQuery($query);
	}	
}