<?php

namespace post\model;

require_once('./post/model/PostDAL.php');

class PostModel {
	/**
	 * Able to query the database.	
	 * @var \post\model\PostDAL
	 */
	private $postDAL;

	/**
	 * Initiates objects.
	 */
	public function __construct() {
		$this->postDAL = new \post\model\PostDAL();
	}

	/**
	 * Retrieves a post with a certain id
	 * from our system.
	 * @param  int $id
	 * @return \post\model\Post
	 */
	public function getPost($id) {
		return $this->postDAL->getPost($id);
	}

	/**
	 * This method saves a post to our system.
	 * @param  \post\model\Post $post
	 * @return \post\model\Post
	 */
	public function savePost(\post\model\Post $post) {
		//By adding the post to our database we
		//get the id.
		$id = $this->postDAL->addPost($post);

		//And now we can set it.
		$post->setId($id);

		//Finally we return the object.
		return $post;
	}

	/**
	 * This method updates an existing
	 * post in our system.
	 * @param  \post\model\Post $post
	 * @return void
	 */
	public function updatePost(\post\model\Post $post) {
		//Update in system.
		$this->postDAL->updatePost($post);
	}

	/**
	 * This method removes a post from our system.
	 * @param  \post\model\Post $post
	 * @return void
	 */
	public function deletePost(\post\model\Post $post) {
		//Remove the post.
		$this->postDAL->deletePost($post);
	}
}