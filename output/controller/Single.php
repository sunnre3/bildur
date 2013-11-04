<?php

namespace output\controller;

require_once('./output/controller/IController.php');
require_once('./post/model/PostModel.php');
require_once('./comment/model/CommentModel.php');
require_once('./post/view/Post.php');
require_once('./comment/view/Comment.php');
require_once('./application/view/AppView.php');

class Single implements IController {
	/**
	 * Model class to retrieve the post from our system.
	 * @var \post\model\PostDAL
	 */
	private $postModel;

	/**
	 * Model class to save the comment to our system
	 * aswell as retrieving them.
	 * @var \comment\model\CommentModel
	 */
	private $commentModel;

	/**
	 * The PostView will draw the front-end to the user.
	 * @var \post\view\Post
	 */
	private $postView;

	/**
	 * We need a CommentView to retrieve our comment.
	 * @var [type]
	 */
	private $commentView;

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
	 * Array containing all the comments belong to
	 * the post we are going to show.
	 * @var \comment\model\Comment[]
	 */
	private $comments;

	/**
	 * Initiates objects.
	 */
	public function __construct() {
		//PostModel.
		$this->postModel = new \post\model\PostModel();

		//CommentModel.
		$this->commentModel = new \comment\model\CommentModel();

		//PostView.
		$this->postView = new \post\view\Post();

		//CommentView.
		$this->commentView = new \comment\view\Comment();

		//AppView.
		$this->appView = new \application\view\AppView();
	}

	/**
	 * Retrieves the ID for the post,
	 * sends it to the DAL class to fetch
	 * the post content from our database.
	 * @return void
	 */
	public function run() {
		//First check if the user just submitted a comment
		//by asking our view.
		if($this->postView->isCommentSubmit()) {
			try {
				//Get the comment-
				$comment = $this->commentView->getComment();

				//Save the comment to the system.
				$this->commentModel->saveComment($comment);
			}

			catch(\Exception $e) {
				//If something goes wrong with creating
				//a comment object we need to show the user
				//some kind of error message.
				$this->postView->newCommentFailed($this->commentView);
			}
		}

		//Then we try to retrieve our post.
		try {
			//PostID.
			$id = $this->postView->getPostId();

			//Get the post from our system.
			$this->post = $this->postModel->getPost($id);

			//Get the comments aswell.
			$this->comments = $this->commentModel->getComments($this->post);
		}

		catch(\Exception $e) {
			$this->appView->redirectToFrontPage();
		}
	}

	public function getContent() {
		return $this->postView->getSingle($this->post, $this->comments);
	}
}