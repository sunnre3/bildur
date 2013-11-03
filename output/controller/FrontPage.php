<?php

namespace output\controller;

require_once('./post/model/PostDAL.php');
require_once('./post/view/Post.php');
require_once('./output/controller/IController.php');

class FrontPage implements IController {
	/**
	 * Private array containing all posts.
	 * This will be populated later.
	 * @var \post\model\Post[]
	 */
	private $posts;

	/**
	 * DAL class to retrieve all posts
	 * from our data source.
	 * @var \post\model\PostDAL
	 */
	private $postsDAL;

	/**
	 * View class for posts.
	 * @var \post\view\Post
	 */
	private $postView;

	/**
	 * Initiate objects.
	 */
	public function __construct() {
		//Initiate DAL object.
		$this->postsDAL = new \post\model\PostDAL();

		//Initiate the postView object.
		$this->postView = new \post\view\Post();
	}

	/**
	 * Runs everything necessary for front page.
	 * @return void
	 */
	public function run() {
		//Get all posts.
		$this->posts = $this->postsDAL->getPosts();
	}

	/**
	 * Returns content required for the front page.
	 * @return string HTML
	 */
	public function getContent() {
		return $this->postView->getAll($this->posts);
	}
}