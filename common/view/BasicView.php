<?php

namespace common\view;

abstract class BasicView {
	/**
	 * These below are strings that will be used in
	 * different forms in our application.
	 */
	protected static $TITLE_FIELD = 'title';
	protected static $IMAGE_UPLOADER = 'uploader';
	protected static $CONTENT_FIELD = 'content';

	protected static $IMAGE_TITLE = 'image_title';
	protected static $IMAGE_CAPTION = 'image_caption';

	protected static $USERNAME_FIELD = 'username';
	protected static $PASSWORD_FIELD = 'password';
	protected static $REPEAT_PASSWORD_FIELD = 'repeat_password';
	protected static $EMAIL_FIELD = 'email';
	protected static $REMEMBER_ME = 'remember_me';
	protected static $SUBMIT_BUTTON = 'submit';

	protected static $COMMENTBODY_FIELD = 'comment_body';
	protected static $COMMENT_SUBMIT_BUTTON = 'comment_submit';

	private static $GET_SHOW_POST_ID = ROUTER_SINGLE_POST;
	private static $GET_EDIT_POST_ID = ROUTER_EDIT_POST;
	private static $GET_DELETE_POST_ID = ROUTER_DELETE_POST;

	/**
	 * String containing message.
	 * @var string
	 */
	protected $message = "";

	/**
	 * Helper method for when registration fails and
	 * you want to add an error message to display to
	 * your user.
	 * @param string $message
	 */
	protected function addErrorMessage($message) {
		$this->message .= "<li class=\"error-message\">{$message}</li>";
	}

	/**
	 * Checks if the user just submit a form by
	 * checking if submit is set in $_POST.
	 * @return boolean
	 */
	public function isPOSTSubmit() {
		return isset($_POST[self::$SUBMIT_BUTTON]);
	}

	/**
	 * Checks if the user just submitted a comment.
	 * @return boolean
	 */
	public function isCommentSubmit() {
		return isset($_POST[self::$COMMENT_SUBMIT_BUTTON]);
	}

	/**
	 * Public function to retrieve the post ID
	 * from the query string.
	 * @return int
	 */
	public function getPostId() {
		if(isset($_GET[self::$GET_EDIT_POST_ID])) {
			//If the value in $_GET isn't numeric; throw.
			if(!is_numeric($_GET[self::$GET_EDIT_POST_ID]))
				throw new \Exception('Post::getPostId() failed: value isn\'t numeric');

			return intval($_GET[self::$GET_EDIT_POST_ID]);
		}

		elseif(isset($_GET[self::$GET_SHOW_POST_ID])) {
			//If the value in $_GET isn't numeric; throw.
			if(!is_numeric($_GET[self::$GET_SHOW_POST_ID]))
				throw new \Exception('Post::getPostId() failed: value isn\'t numeric');

			return intval($_GET[self::$GET_SHOW_POST_ID]);
		}

		elseif(isset($_GET[self::$GET_DELETE_POST_ID])) {
			//If the value in $_GET isn't numeric; throw.
			if(!is_numeric($_GET[self::$GET_DELETE_POST_ID]))
				throw new \Exception('Post::getPostId() failed: value isn\'t numeric');

			return intval($_GET[self::$GET_DELETE_POST_ID]);
		}

		//If we didn't find anything; throw.
		throw new \Exception('Post::getPostId() failed: no id was found');
	}
}