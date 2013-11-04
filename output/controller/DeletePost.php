<?php

namespace output\controller;

require_once('./output/controller/IController.php');
require_once('./post/model/PostModel.php');
require_once('./post/view/Post.php');
require_once('./application/view/AppView.php');

class DeletePost implements IController {
	/**
	 * We need a PostModel in order
	 * to delete a post.
	 * @var \post\model\PostModel
	 */
	private $postModel;

	/**
	 * We need a PostView in order
	 * to get the post id.
	 * @var \post\view\Post
	 */
	private $postView;

	/**
	 * We need an AppView in order
	 * to redirect the user.
	 * @var \application\view\AppView
	 */
	private $appView;

	/**
	 * Representation of the post.
	 * @var \post\model\Post
	 */
	private $post;

	public function __construct() {
		//PostModel.
		$this->postModel = new \post\model\PostModel();

		//PostView.
		$this->postView = new \post\view\Post();

		//AppView.
		$this->appView = new \application\view\AppView();
	}

	/**
	 * Simply deletes a post from
	 * our system.
	 * @return void
	 */
	public function run() {
		try {
			//Get the post id.
			$postID = $this->postView->getPostId();

			//Get the post.
			$this->post = $this->postModel->getPost($postID);

			//Delete the post.
			$this->postModel->deletePost($this->post);

			//Redirect to front page.
			$this->appView->redirectToFrontPage();
		}

		catch(\Exception $e) {
			//Redirect to front page.
			$this->appView->redirectToFrontPage();
		}
	}

	/**
	 * This method is left empty because
	 * we will actually redirect the user
	 * to the front page after the post
	 * has been deleted.
	 * @return void
	 */
	public function getContent() {}
}