<?php

namespace post\view;

require_once('./common/view/BasicView.php');
require_once('./login/model/Login.php');
require_once('./image/model/ImageDAL.php');
require_once('./user/model/UserDAL.php');
require_once('./image/view/Image.php');
require_once('./comment/view/Comment.php');
require_once('./post/model/Post.php');

class Post extends \common\view\BasicView {
	private static $ERROR_MISSING_TITLE = 'Du måste ange en titel.';
	private static $ERROR_BAD_UPLOAD = 'Du kan bara ladda upp filer som slutar på 
										antingen .jpeg, .jpg, .gif eller .png';

	private static $ERROR_MISSING_COMMENTBODY = 'En kommentar får inte vara tom.';
	private static $ERROR_COMMENT_MISC = 'Något gick fel. Testa igen och kontakta systemadmin
										  om problemet kvarstår.';

	/**
	 * LoginModel to get the logged in user etc.
	 * @var \login\model\Login
	 */
	private $loginModel;
	
	/**
	 * DAL class for fetching images
	 * @var \image\model\ImageDAL
	 */
	private $imageDAL;

	/**
	 * DAL class for fetching the user
	 * @var \user\model\UserDAL
	 */
	private $userDAL;

	/**
	 * ImageView to get the HTML for
	 * images.
	 * @var \image\view\Image
	 */
	private $imageView;

	/**
	 * CommentView to get the HTML
	 * for comments.
	 * @var \comment\view\Comment
	 */
	private $commentView;

	/**
	 * Initiates objects.
	 */
	public function __construct() {
		//LoginModel.
		$this->loginModel = new \login\model\Login();

		//ImageDAL.
		$this->imageDAL = new \image\model\ImageDAL();

		//UserDAL.
		$this->userDAL = new \user\model\UserDAL();

		//ImageView.
		$this->imageView = new \image\view\Image();

		//CommentView.
		$this->commentView = new \comment\view\Comment();
	}

	/**
	 * Returns a new \post\model\Post
	 * @return \post\model\Post
	 */
	public function getPost() {
		return \post\model\Post::uploaded($this->getTitle(),
										  $this->getDateTime(),
										  $this->getContent());
	}

	/**
	 * Returns an reorganized version of $_FILES.
	 * @return string[]
	 */
	public function getFiles() {
		$names = array(
			'name' => 1,
			'type' => 1,
			'tmp_name' => 1,
			'error' => 1,
			'size' => 1
			);

		$files = array();

		foreach($_FILES[parent::$IMAGE_UPLOADER] as $key => $part) {
			$key = (string) $key;
			if(isset($names[$key]) && is_array($part)) {
				foreach($part as $position => $value) {
					$files[$position][$key] = $value;
				}
			}
		}

		return $files;
	}

	/**
	 * Returns a HTML string for the form
	 * needed to create a new post.
	 * @return string HTML
	 */
	public function getNewPostForm() {
		$message = "<div class=\"grid-container errors\"><ul>{$this->message}</ul></div>";

		return '
			<article id="new-post" class="post">
				<div class="grid-50">
					<header class="post-header">
						<h1>Skapa ett nytt inlägg</h1>
					</header>

					<p>Använd formuläret bredvid för att skapa 
					ett nytt inlägg! Det enda du måste tänka på är 
					att ett inlägg <strong>måste</strong> ha minst en 
					titel och <strong>1</strong> bild.<p>
				</div>

				<section id="post-form" class="grid-50">'

					. $message .

					'<form method="post" enctype="multipart/form-data">
						<div class="form-group">
							<input type="text" name="' . parent::$TITLE_FIELD . '" placeholder="Inläggets titel">
						</div>

						<div class="form-group">
							<label for="' . parent::$IMAGE_UPLOADER . '">Ladda upp bild</label>
							<input type="file" name="' . parent::$IMAGE_UPLOADER . '[]" id="' . parent::$IMAGE_UPLOADER . '" multiple>
							<small>Observera att du kan välja mer än en bild!</small>
						</div>

						<div class="form-group">
							<label for="' . parent::$CONTENT_FIELD . '">Brödtext</label>
							<textarea name="' . parent::$CONTENT_FIELD . '" id="' . parent::$CONTENT_FIELD . '"></textarea>
						</div>

						<div class="form-group">
							<input type="submit" value="Skapa" class="btn btn-blue" name="' . parent::$SUBMIT_BUTTON . '">
						</div>
					</form>
				</section>
			</article>';
	}

	/**
	 * Returns a HTML string for the form
	 * needed to edit an existed post.
	 * @param  \post\model\Post $post
	 * @return string HTML
	 */
	public function getEditPostForm(\post\model\Post $post) {
		$message = "<div class=\"grid-container errors\"><ul>{$this->message}</ul></div>";

		//Return HTML.
		return '
			<article id="edit-form" class="post">
				<header class="post-header">
					<h1>Redigera inlägg</h1>
				</header>

				<section id="edit-form">'
					
					. $message .

					'<form method="post" enctype="multipart/form-data">
						<div class="form-group">
							<label for="' . self::$TITLE_FIELD . '">Titel</label>
							<input type="text" name="' . self::$TITLE_FIELD . '" value="' . $post->getTitle() . '">
						</div>

						<div class="form-group">
							<label for="' . self::$CONTENT_FIELD . '">Brödtext</label>
							<textarea name="' . self::$CONTENT_FIELD . '">' . $post->getContent() . '</textarea>
						</div>

						<div class="form-group">
							<input type="submit" class="btn btn-green" name="' . parent::$SUBMIT_BUTTON . '" value="Spara">
						</div>
					</form>
				</section>
			</article>';
	}

	/**
	 * Returns a HTML string for a single post.
	 * @param  \post\model\Post $post
	 * @param  \comment\model\Coment[] $comments
	 * @return string HTML
	 */
	public function getSingle(\post\model\Post $post, $comments) {
		//Error messages.
		$message = "<div class=\"grid-container errors\"><ul>{$this->message}</ul></div>";

		//Set up some local variables.
		$id = $post->getId();
		$title = $post->getTitle();
		$content = $post->getContent();

		//Get all images associated to this post.
		$post_images = $this->imageDAL->getImages($post);

		//Get the HTML for the images.
		$images_html = $this->imageView->getAll($post_images);

		//Get our User.
		$user = $this->userDAL->getUser($post->getUserId());

		//Get the comment form.
		$comment_form_html = $this->commentView->getCommentForm();

		//Generate HTML for comments.
		$comments_html = $this->commentView->getComments($comments);

		//If the user is logged in and is
		//the same user who created this post
		//we should show the option to edit.
		$user_action = ($this->loginModel->isLoggedIn() && $this->loginModel->getLoggedInUser()->compareUsername($user)) ?
			'<div id="post-menu">
				<a href="' . ROUTER_PREFIX . ROUTER_EDIT_POST . ROUTER_INFIX  . $post->getId() . '">Redigera inlägg</a>
				<a href="' . ROUTER_PREFIX . ROUTER_DELETE_POST . ROUTER_INFIX . $post->getId() . '">Ta bort inlägg</a>
			</div>' : '';

		return "
			<article id=\"post-{$id}\" class=\"post single\">
				<header class=\"post-header\">
					<h1>{$title}</h1>
				</header>

				{$user_action}

				<p>{$content}</p>

				{$images_html}
			</article>

			<section id=\"post-comments\">
				{$comments_html}
			</section>

			<section id=\"comment-form\">
				{$message}
				{$comment_form_html}
			</section>
			";
	}

	/**
	 * Returns a HTML string for all posts.
	 * @param \post\model\Post[] $posts
	 * @return string HTML
	 */
	public function getAll($posts) {
		//HTML string.
		$html = '<div id="front-page-posts">';

		//Loop through all posts to append HTML.
		foreach($posts as $key => $post) {
			$id = $post->getId();
			$title = $post->getTitle();
			$content = $post->getContent();

			$thumbnail = $this->imageDAL->getThumbnail($post);
			$thumbnail_filepath = $thumbnail->getThumbnail();

			$html .= '
				<div id="post-' . $id . '" class="front-page post">
					<a href="' . ROUTER_PREFIX . ROUTER_SINGLE_POST . ROUTER_INFIX . $id . '">
						<img src="' . $thumbnail_filepath . '" alt="">
					</a>
				</div>';
		}

		$html .= '</div>';

		//Return.
		return $html;
	}	

	/**
	 * Try and determine what went wrong in
	 * creating a new post and show the appropriate
	 * error message to the user.
	 * @return void
	 */
	public function postFailed() {
		//Check if there was a title.
		if($this->getTitle() == "") {
			$this->addErrorMessage(self::$ERROR_MISSING_TITLE);
		}

		//If there was a title but we still had an error
		//it was probably something wrong the uploaded file.
		else {
			$this->addErrorMessage(self::$ERROR_BAD_UPLOAD);
		}
	}

	/**
	 * When a user tries to comment and it fails
	 * we need to show the user why.
	 * @param  \comment\view\Comment $commentView
	 * @return void
	 */
	public function newCommentFailed(\comment\view\Comment $commentView) {
		//Check if the comment content was empty.
		if(trim($commentView->getCommentBody()) == "") {
			$this->addErrorMessage(self::$ERROR_MISSING_COMMENTBODY);
		}

		//If it wasn't, then it was most likely a
		//back-end problem.
		else {
			$this->addErrorMessage(self::$ERROR_COMMENT_MISC);
		}
	}

	/**
	 * Returns title.
	 * @return string
	 */
	private function getTitle() {
		if(isset($_POST[parent::$TITLE_FIELD]))
			return $_POST[parent::$TITLE_FIELD];

		return "";
	}

	/**
	 * Returns current time.
	 * @return datetime
	 */
	private function getDateTime() {
		return date("Y-m-d H:i:s");
	}

	/**
	 * Returns the content.
	 * @return string
	 */
	private function getContent() {
		if(isset($_POST[parent::$CONTENT_FIELD]))
			return $_POST[parent::$CONTENT_FIELD];

		return "";
	}
}