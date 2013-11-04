<?php

namespace output\controller;

require_once('./output/controller/IController.php');
require_once('./post/model/PostModel.php');
require_once('./image/model/ImageModel.php');
require_once('./login/model/Login.php');
require_once('./post/view/Post.php');
require_once('./application/view/AppView.php');

class NewPost implements IController {
	/**
	 * Our model class knows how to create a new
	 * post so we use it when we have all data
	 * we need.
	 * @var \post\model\Post
	 */
	private $postModel;

	/**
	 * We need an ImageModel to create Image objects
	 * from the files we retrieve from the View.
	 * @var [type]
	 */
	private $imageModel;

	/**
	 * We need a loginModel to make sure we're logged in.
	 * @var \login\model\Login
	 */
	private $loginModel;

	/**
	 * Our postView will show the form for the user
	 * aswell as retrieve data for controller.
	 * @var \post\view\Post
	 */
	private $postView;

	/**
	 * AppView can do redirects etc.
	 * @var \application\view\AppView;
	 */
	private $appView;

	/**
	 * Initiates objects.
	 */
	public function __construct() {
		//PostModel.
		$this->postModel = new \post\model\PostModel();

		//ImageModel.
		$this->imageModel = new \image\model\ImageModel();

		//LoginModel.
		$this->loginModel = new \login\model\Login();

		//PostView.
		$this->postView = new \post\view\Post();

		//AppView.
		$this->appView = new \application\view\AppView();
	}

	/**
	 * First check if a user has submitted
	 * a new post through the form and if
	 * that evaluates to true then we proceed
	 * to create a new post with our model.
	 * @return void
	 */
	public function run() {
		//If the user isn't logged in he should be
		//redirected to the front page.
		if(!$this->loginModel->isLoggedIn())
			$this->appView->redirectToFrontPage();

		//If the user just pressed submit,
		//that means it's go time.
		if($this->postView->isPOSTSubmit()) {
			try {
				//Get the post.
				$post = $this->postView->getPost();

				//Get the logged in user.
				$user = $this->loginModel->getLoggedInUser();

				//Set user id to current user.
				$post->setUserId($user->getId());

				//Save the post.
				$post = $this->postModel->savePost($post);

				//Get the files.
				$files = $this->postView->getFiles();

				//Create an array.
				$images = array();

				//Loop through the files to
				//validate that each one of them
				//is an image. If they are valid
				//they will be saved to our system
				//and the method will return an
				//Image object.
				foreach($files as $key => $file) {
					$images[] = $this->imageModel->validateImage($file);
				}

				//Set the postID to all our image objects
				//and then save them to our database.
				foreach($images as $key => $image) {
					//Set postID.
					$image->setPostId($post->getId());

					//Save to database.
					$this->imageModel->saveImage($image);
				}

				//Finally we redirect user to the new post.
				$this->appView->redirectToPost($post);
			}

			catch(\Exception $e) {
				//Make a temporary post.
				$tmp_post = isset($post) ? $post : null;

				//If something went wrong with creating a new
				//post we want to reverse everything that was done
				//before the error occured so not to mess anything up.
				if($tmp_post != null) {
					$this->postModel->deletePost($post);
				}

				//Then we also want to show the
				//user some kind of error message.
				$this->postView->postFailed();
			}
		}
	}

	/**
	 * Returns HTML containing the form
	 * for a new post.
	 * @return string HTML
	 */
	public function getContent() {
		return $this->postView->getNewPostForm();
	}
}