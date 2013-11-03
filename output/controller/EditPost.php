<?php

namespace output\controller;

require_once('./output/controller/IController.php');

class EditPost implements IController {
	/**
	 * We need a PostModel in order to save
	 * a newly edited post.
	 * @var \post\model\Post
	 */
	private $postModel;

	/**
	 * We need a PostView to recieve the
	 * new post along with HTML.
	 * @var \post\view\Post
	 */
	private $postView;

	/**
	 * Initiates objects.
	 */
	public function __construct() {
		//PostModel.
		$this->postModel = new \post\model\PostModel();

		//PostView.
		$this->postView = new \post\view\Post();
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

		}
	}

	/**
	 * This method returns the necessary HTML
	 * from our view.
	 * @return string HTML
	 */
	public function getContent() {
		return $this->postView->getEditPostForm();
	}
}