<?php

namespace post\view;

require_once('./common/view/BasicView.php');
require_once('./post/model/Post.php');
require_once('./image/model/ImageDAL.php');
require_once('./image/view/Image.php');
require_once('./login/model/Login.php');
require_once('./user/model/UserDAL.php');

class Post extends \common\view\BasicView {
	private static $GET_POSTID_INDEX = ROUTER_SINGLE_POST;

	private static $ERROR_MISSING_TITLE = 'Du måste ange en titel.';
	private static $ERROR_BAD_UPLOAD = 'Du kan bara ladda upp filer som slutar på 
										antingen .jpeg, .jpg, .gif eller .png';

	/**
	 * DAL class for fetching images
	 * @var \image\model\ImageDAL
	 */
	private $imageDAL;

	/**
	 * Initiates objects.
	 */
	public function __construct() {
		//Get an ImageDAL object.
		$this->imageDAL = new \image\model\ImageDAL();
	}

	/**
	 * Public function to retrieve the post ID
	 * from the query string.
	 * @return int
	 */
	public function getPostId() {
		//If no id is set in the $_GET array; throw.
		if(!isset($_GET[self::$GET_POSTID_INDEX]))
			throw new \Exception('Post::getPostId() failed: no id was found');

		elseif(!is_numeric($_GET[self::$GET_POSTID_INDEX]))
			throw new \Exception('Post::getPostId() failed: value is not numeric');

		return intval($_GET[self::$GET_POSTID_INDEX]);
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
							<input type="file" name="' . parent::$IMAGE_UPLOADER . '[]" multiple>
							<small>Observera att du kan välja mer än en bild!</small>
						</div>

						<div class="form-group">
							<label for="' . parent::$CONTENT_FIELD . '">Brödtext</label>
							<textarea name="' . parent::$CONTENT_FIELD . '"></textarea>
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
	 * @return string HTML
	 */
	public function getEditPostForm() {
		$message = "<div class=\"grid-container errors\"><ul>{$this->message}</ul></div>";

		return '';
	}

	/**
	 * Returns a HTML string for a single post.
	 * @param  \post\model\Post $post
	 * @return string HTML
	 */
	public function getSingle(\post\model\Post $post) {
		//We need a LoginModel to check if the user is logged in.
		$loginModel = new \login\model\Login();

		//We need an ImageView to get our images.
		$imageView = new \image\view\Image();

		//Lastly we need a UserDAL to get our user.
		$userDAL = new \user\model\UserDAL();

		//Set up some local variables.
		$id = $post->getId();
		$title = $post->getTitle();
		$content = $post->getContent();

		//Get all images associated to this post.
		$post_images = $this->imageDAL->getImages($post);

		//Get the HTML for the images.
		$images_html = $imageView->getAll($post_images);

		//Get our User.
		$user = $userDAL->getUser($post->getUserId());

		//If the user is logged in and is
		//the same user who created this post
		//we should show the option to edit.
		$user_action = ($loginModel->isLoggedIn() && $loginModel->getLoggedInUser()->compareUsername($user)) ?
			'<div id="post-menu">
				<a href="' . ROUTER_PREFIX . ROUTER_EDIT_POST . ROUTER_INFIX  . $post->getId() . '">Redigera inlägg</a>
			</div>' : '';

		return "
			<article id=\"post-{$id}\" class=\"post single\">
				<header class=\"post-header\">
					<h1>{$title}</h1>
				</header>

				{$user_action}

				<p>{$content}</p>

				{$images_html}
			</article>";
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
			$caption = $thumbnail->getCaption();

			$html .= '
				<div id="post-' . $id . '" class="front-page post">
					<a href="' . ROUTER_PREFIX . ROUTER_SINGLE_POST . ROUTER_INFIX . $id . '">
						<img src="' . $thumbnail_filepath . '" alt="' . $caption . '">
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
	public function newPostFailed() {
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