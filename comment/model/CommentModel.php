<?php

namespace comment\model;

require_once('./comment/model/CommentDAL.php');

class CommentModel {
	/**
	 * DAL class to retrieve, save, delete
	 * and update comments in our system.
	 * @var \comment\model\CommentDAL
	 */
	private $commentDAL;

	/**
	 * Initiate a CommentDAL.
	 */
	public function __construct() {
		$this->commentDAL = new \comment\model\CommentDAL();
	}

	/**
	 * Retrieves all comments belong to a single
	 * post in our system.
	 * @param  \post\model\Post $post
	 * @return \comment\model\Comment[]
	 */
	public function getComments(\post\model\Post $post) {
		return $this->commentDAL->getComments($post);
	}

	/**
	 * Saves a new comment in to our system.
	 * @param  \comment\model\Comment $comment
	 * @return void
	 */
	public function saveComment(\comment\model\Comment $comment) {
		$this->commentDAL->addComment($comment);
	}

	/**
	 * Edits an already existing comment in
	 * our system.
	 * @param  \comment\model\Comment $comment
	 * @return void
	 */
	public function editComment(\comment\model\Comment $comment) {
		$this->commentDAL->updateComment($comment);
	}

	/**
	 * Deletes a comment by its ID 
	 * from our system.
	 * @param  int $id
	 * @return void
	 */
	public function deleteCommentById($id) {
		$this->commentDAL->deleteCommentById($id);
	}
}