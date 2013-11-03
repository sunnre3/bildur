<?php

namespace output\controller;

require_once('./output/controller/IController.php');

class Single implements IController {
	/**
	 * @var \post\view\Post
	 */
	private $postView;

	/**
	 * AppView can do things as redirections.
	 * @var \application\view\AppView
	 */
	private $appView;

	/**
	 * This will be populated later when we execute the
	 * run() method on this controller. One single post
	 * because this is the single controller.
	 * @var \post\model\Post
	 */
	private $post;

	/**
	 * DAL class to retrieve our post.
	 * @var \post\model\PostDAL
	 */
	private $postDAL;

	/**
	 * Initiates objects.
	 */
	public function __construct() {
		//PostView.
		$this->postView = new \post\view\Post();

		//AppView.
		$this->appView = new \application\view\AppView();

		//PostDAL
		$this->postDAL = new \post\model\PostDAL();
	}

	/**
	 * Retrieves the ID for the post,
	 * sends it to the DAL class to fetch
	 * the post content from our database.
	 * @return void
	 */
	public function run() {
		try {
			//PostID.
			$id = $this->postView->getPostId();

			//Get the post from our DAL.
			$this->post = $this->postDAL->getPost($id);
		}

		catch(\Exception $e) {
			$this->appView->redirectToFrontPage();
		}
	}

	public function getContent() {
		return $this->postView->getSingle($this->post);
	}
}