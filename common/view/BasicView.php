<?php

namespace common\view;

abstract class BasicView {
	/**
	 * These below are strings that will be used in
	 * different forms in our application.
	 *
	 * The first three are used in the new post form
	 * and the last ones are for registration and login.
	 */
	protected static $TITLE_FIELD = 'title';
	protected static $IMAGE_UPLOADER = 'uploader';
	protected static $CONTENT_FIELD = 'content';

	protected static $USERNAME_FIELD = 'username';
	protected static $PASSWORD_FIELD = 'password';
	protected static $REPEAT_PASSWORD_FIELD = 'repeat_password';
	protected static $SUBMIT_BUTTON = 'submit';

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
}