<?php

namespace comment\model;

require_once('./database/model/Base.php');
require_once('./comment/model/Comment.php');

class CommentDAL extends \database\model\Base {
	/**
	 * This method retrieves all the comments in
	 * our database that belongs to a certain post.
	 * Returns an array of Comment object.
	 * @param  \post\model\Post $post
	 * @return \comment\model\Comment[]
	 */
	public function getComments(\post\model\Post $post) {
		//Create an array.
		$comments = array();

		//MySQL query.
		$query = 'SELECT * FROM ' . parent::$COMMENT_TABLE_NAME . ' WHERE post_id=' . $post->getId() . ' ORDER BY datetime DESC';

		//Set of result.
		$result = $this->executeQuery($query);

		//Loop through result.
		while($obj = $result->fetch_array(MYSQLI_ASSOC)) {
			//Create an Comment object.
			$comments[] = new \comment\model\Comment($obj['id'],
													 $obj['post_id'],
													 $obj['user_id'],
													 $obj['datetime'],
													 $obj['content']);
		}

		//Return the array.
		return $comments;
	}

	/**
	 * This method saves a new comment to
	 * our database.
	 * @param \comment\model\Comment $comment
	 * @return void
	 */
	public function addComment(\comment\model\Comment $comment) {
		//MySQL query.
		$query = "INSERT INTO " . parent::$COMMENT_TABLE_NAME . " (post_id, user_id, datetime, content) 
		VALUES({$comment->getpostId()}, {$comment->getUserId()}, '{$comment->getDateTime()}', '{$comment->getContent()}')";

		//Execute query.
		$this->executeQuery($query);
	}

	/**
	 * This method updates a comment in our database.
	 * @param  \comment\model\Comment $comment
	 * @return void
	 */
	public function updateComment(\comment\model\Comment $comment) {
		//MySQL query.
		$query = "UPDATE " . parent::$COMMENT_TABLE_NAME .
		" SET datetime='{$comment->getDateTime()}', content='{$comment->getContent()}'
		 WHERE id={$comment->getId()}";

		//Execute query.
		$result = $this->executeQuery($query);
	}

	/**
	 * This method deletes a comment from our database
	 * with a certain id.
	 * @param  int $id
	 * @return void
	 */
	public function deleteCommentById($id) {
		//MySQL query.
		$query = 'DELETE FROM ' . parent::$COMMENT_TABLE_NAME . ' WHERE id=' . $id;

		//Execute query.
		$this->executeQuery($query);
	}
}