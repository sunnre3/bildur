<?php

namespace comment\view;

require_once('./common/view/BasicView.php');
require_once('./user/model/UserDAL.php');
require_once('./login/model/Login.php');

class Comment extends \common\view\BasicView {
	/**
	 * UserDAL to get our user.
	 * @var \user\model\UserDAL
	 */
	private $userDAL;

	/**
	 * LoginModel to get the
	 * currently logged in
	 * user.
	 * @var \login\model\Login
	 */
	private $loginModel;

	/**
	 * Initiates objects.
	 */
	public function __construct() {
		//UserDAL.
		$this->userDAL = new \user\model\UserDAL();

		//LoginModel.
		$this->loginModel = new \login\model\Login();
	}

	/**
	 * Fetches the newly added comment.
	 * @return \comment\model\Comment
	 */
	public function getComment() {
		return \comment\model\Comment::__new($this->getPostId(),
											 $this->getUserId(),
											 $this->getDateTime(),
											 $this->getCommentBody()
											);
	}

	/**
	 * Returns the HTML necessary for commenting
	 * on a post.
	 * @return string HTML
	 */
	public function getCommentForm() {
		if($this->loginModel->isLoggedIn()) {
			$html = '
					<form method="post">
						<div class="form-group">
							<textarea name="' . parent::$COMMENTBODY_FIELD . '" placeholder="Din kommentar"></textarea>
						</div>

						<div class="form-group">
							<input type="submit" class="btn btn-green" value="Skicka" name="' . parent::$COMMENT_SUBMIT_BUTTON . '">
						</div>
					</form>
			';
		}

		else {
			$html = '
					<h1 class="anon">Du måste vara inloggad för att kommentera.</h1>
			';
		}

		return $html;
	}

	/**
	 * Returns the HTML required for presenting
	 * every comment that we recieve.
	 * @param  \comment\model\Comment[] $comments
	 * @return string HTML
	 */
	public function getComments($comments) {
		//HTML string.
		$html = '';

		//Loop through the comments.
		foreach($comments as $key => $comment) {
			//Get the user.
			$user = $this->userDAL->getUser($comment->getUserId());

			//Get the gravatar URL.
			$grav_url = "http://www.gravatar.com/avatar/" . md5(strtolower(trim($user->getEmail()))) . "?d=mm&s=60";

			$loggedInUser = null;

			//If the user is logged in we need to retrieve
			//that object aswell from the Loginmodel.
			if($this->loginModel->isLoggedIn())
				$loggedInUser = $this->loginModel->getLoggedInUser();

			//If the current comment user is the same as
			//the person who is logged in then we can add
			//the options to delete and edit the comment.
			$action = ($loggedInUser != null && $user->getId() == $loggedInUser->getId()) ?
					'
					<div class="comment-actions clearfix">
						<a href="#" class="comment-btn" data-ac="edit-comment">Redigera</a>
						<a href="#" class="comment-btn" data-ac="delete-comment">Ta bort</a>
					</div>
					' : '';

			$html .= '
				<article id="comment-id-' . $comment->getId() . '" data-id="' . $comment->getId() . '" class="comment clearfix">
					<div class="gravatar">
						<img src="' . $grav_url . '" alt="gravatar">
					</div>

					<div class="content">
						<header class="comment-header">
							<h1>' . $user->getUsername() . '</h1>
							skrev den ' . $comment->getDateTime() . '
						</header>'

						. $action .

						'<p>' . $comment->getContent() . '</p>
					</div>
				</article>
				';
		}

		return $html;
	}

	/**
	 * Public helper method to retrieve the body
	 * for the comment.
	 * @return string
	 */
	public function getCommentBody() {
		if(isset($_POST[parent::$COMMENTBODY_FIELD]))
			return $_POST[parent::$COMMENTBODY_FIELD];

		return "";
	}

	/**
	 * Private helper method to retrieve the
	 * current timestamp.
	 * @return datetime
	 */
	private function getDateTime() {
		return date('Y-m-d H:i:s');
	}

	/**
	 * Private helper method to retrieve the
	 * userId for the currently logged in user.
	 * @return int
	 */
	private function getUserId() {
		//Make sure the user is logged in.
		assert($this->loginModel->isLoggedIn());

		//Return the userId.
		return $this->loginModel->getLoggedInUser()->getId();
	}
}