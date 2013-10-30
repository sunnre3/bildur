<?php

namespace post\model;

require_once("./database/model/Base.php");
require_once("./post/model/Post.php");

class PostDAL extends \database\model\Base {
	/**
	 * Opens a database connection, retrieves all posts
	 * and creates an array of \post\model\Posts.
	 * @return \post\model\Post[]
	 */
	public function getPosts() {
		//Create an array.
		$posts = array();

		//MySQL query
		$query = "SELECT * FROM " . parent::$POST_TABLE_NAME;

		//Set of result
		$result = $this->executeQuery($query);

		//Loop through the result and create objects.
		while($obj = $result->fetch_array(MYSQLI_ASSOC)) {
			$posts[] = new \post\model\Post($obj["id"],
								 $obj["user_id"],
								 $obj["title"],
								 $obj["datetime"],
								 $obj["content"]);
		}

		//Return posts.
		return $posts;
	}
}