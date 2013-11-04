<?php

namespace output\controller;

require_once('./output/controller/IController.php');
require_once('./post/model/PostModel.php');
require_once('./post/model/PostDAL.php');
require_once('./post/view/Post.php');
require_once('./login/model/Login.php');
require_once('./application/view/AppView.php');

class EditPost implements IController {
	/**
	 * We need a PostModel in order to save
	 * a newly edited post.
	 * @var \post\model\Post
	 */
	private $postModel;

	/**
	 * We need a LoginModel to retrieve
	 * the currently logged in user.
	 * @var \login\model\Login
	 */
	private $loginModel;

	/**
	 * We need a PostDAL to retrieve our post.
	 * @var \post\model\PostDAL
	 */
	private $postDAL;

	/**
	 * We need a PostView to recieve the
	 * new post along with HTML.
	 * @var \post\view\Post
	 */
	private $postView;

	/**
	 * We need an AppView to redirect the
	 * user the the newly edited post.
	 * @var \application\view\AppView
	 */
	private $appView;

	/**
	 * The post object to be edited.
	 * @var \post\model\Post
	 */
	private $post;

	/**
	 * Initiates objects.
	 */
	public function __construct() {
		//PostModel.
		$this->postModel = new \post\model\PostModel();

		//LoginModel.
		$this->loginModel = new \login\model\Login();

		//PostDAL.
		$this->postDAL = new \post\model\PostDAL();

		//PostView.
		$this->postView = new \post\view\Post();

		//Appview.
		$this->appView = new \application\view\AppView();

		//Get the postID.
		$id = $this->postView->getPostId();

		//Get the post.
		$this->post = $this->postDAL->getPost($id);
	}

	/**
	 * This method checks first if the post exists,
	 * and if it does then we proceed to edit the post.
	 * @return void
	 */
	public function run() {
		//First check if the user just submitted
		//an edited post.
		if($this->postView->isPOSTSubmit()) {
			try {	
				//Get the new post.
				$post = $this->postView->getPost();

				//Set post id.
				$post->setId($this->postView->getPostId());

				//Get current user.
				$user = $this->loginModel->getLoggedInUser();

				//Set user id.
				$post->setUserId($user->getId());

				//Save the post.
				$this->postModel->updatePost($post);

				//Redirect.
				$this->appView->redirectToPost($post);
			}

			catch(\Exception $e) {
				//Show the user error messages.
				$this->postView->postFailed();
			}
		}
	}

	/**
	 * This method returns the necessary HTML
	 * from our view.
	 * @return string HTML
	 */
	public function getContent() {
		return $this->postView->getEditPostForm($this->post);
	}
}