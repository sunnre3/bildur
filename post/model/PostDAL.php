<?php

namespace post\model;

require_once('./database/model/Base.php');
require_once('./post/model/Post.php');

class PostDAL extends \database\model\Base {
	/**
	 * Adds a post to our database.
	 * @param \post\model\Post $post
	 */
	public function addPost(\post\model\Post $post) {
		//Get a MySQLi object.
		$mysqli = $this->getDBObject();

		//MySQL query.
		$query = "INSERT INTO " . parent::$POST_TABLE_NAME . " (user_id, title, datetime, content)
		VALUES('{$post->getUserId()}', '{$post->getTitle()}', '{$post->getDateTime()}', '{$post->getContent()}')";

		//Execute query.
		$result = $this->executeQuery($query, $mysqli);

		//Return the ID.
		return $mysqli->insert_id;
	}

	/**
	 * Updates a post in our database.
	 * @param  \post\model\Post $post
	 * @return void
	 */
	public function updatePost(\post\model\Post $post) {
		//MySQL query.
		$query = "UPDATE " . parent::$POST_TABLE_NAME .
		" SET title='{$post->getTitle()}', datetime='{$post->getDateTime()}', content='{$post->getContent()}'
		 WHERE id={$post->getId()}";

		//Execute query.
		$result = $this->executeQuery($query);
	}

	/**
	 * Deletes a post from our database.
	 * @param  \post\model\Post $post
	 * @return void
	 */
	public function deletePost(\post\model\Post $post) {
		//MySQL query.
		$query = 'DELETE FROM ' . parent::$POST_TABLE_NAME . ' WHERE id=' . $post->getId();

		//Execute query.
		 $result = $this->executeQuery($query);
	}

	/**
	 * Retrieves one single post.
	 * @param  int $id
	 * @return \post\model\Post
	 */
	public function getPost($id) {
		//MySQL query.
		$query = 'SELECT * FROM ' . parent::$POST_TABLE_NAME . ' WHERE id=' . $id;

		//Result.
		$result = $this->executeQuery($query);

		//Get the first row.
		$obj = $result->fetch_array(MYSQLI_ASSOC);

		//Return a Post object.
		return new \post\model\Post($obj['id'],
									$obj['user_id'],
									$obj['title'],
									$obj['datetime'],
									$obj['content']);
	}

	/**
	 * Opens a database connection, retrieves all posts
	 * and creates an array of \post\model\Posts.
	 * @return \post\model\Post[]
	 */
	public function getPosts() {
		//Create an array.
		$posts = array();

		//MySQL query.
		$query = 'SELECT * FROM ' . parent::$POST_TABLE_NAME . ' ORDER BY datetime DESC';

		//Set of result.
		$result = $this->executeQuery($query);

		//Loop through the result and create objects.
		while($obj = $result->fetch_array(MYSQLI_ASSOC)) {
			$posts[] = new \post\model\Post($obj['id'],
										    $obj['user_id'],
									  	    $obj['title'],
								 		    $obj['datetime'],
								 			$obj['content']);
		}

		//Return posts.
		return $posts;
	}
}