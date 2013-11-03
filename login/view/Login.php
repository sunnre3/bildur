<?php

namespace login\view;

require_once('./common/view/BasicView.php');

class Login extends \common\view\BasicView {
	private static $ERROR_USERNAME_MISSING = 'Användarnamn saknas.';
	private static $ERROR_PASSWORD_MISSING = 'Lösenord saknas.';
	private static $ERROR_MISSING_USER = 'Felaktig kombination av användarnamn/lösenord.';

	/**
	 * Private helper method for getting
	 * username from either $_POST or $_COOKIE.
	 * @return string username
	 */
	protected function getUsername() {
		if(isset($_POST[parent::$USERNAME_FIELD]))
			return $_POST[parent::$USERNAME_FIELD];

		return "";
	}

	/**
	 * Private helper method for getting
	 * username from either $_POST or $_COOKIE.
	 * @return string password
	 */
	protected function getPassword() {
		if(isset($_POST[parent::$PASSWORD_FIELD]))
			return $_POST[parent::$PASSWORD_FIELD];

		return "";
	}

	/**
	 * Private helper method for getting
	 * the repeated password from $_POST.
	 * @return string password
	 */
	protected function getRepeatedPassword() {
		if(isset($_POST[parent::$REPEAT_PASSWORD_FIELD]))
			return $_POST[parent::$REPEAT_PASSWORD_FIELD];

		return "";
	}

	/**
	 * Returns a User object based on
	 * user input.
	 * @return \user\model\User
	 */
	public function getUser() {
		return \user\model\User::cleartext($this->getUsername(),
												 $this->getPassword(),
												 $this->getRepeatedPassword());
	}

	/**
	 * HTML with login form.
	 * @return string HTML
	 */
	public function getLoginForm() {
		$message = "<div class=\"grid-container errors\"><ul>{$this->message}</ul></div>";

		//HTML string.
		$html = '
			<article id="login-page" class="post">
				<header class="post-header">
					<h1>Logga in</h1>
				</header>

				<p>Logga in för att kunna ladda upp bilder och för att 
				lämna ditt egna alias när du kommenterar!</p>

				<section id="login-form" class="prefix-15 grid-70 suffix-15">'

					. $message .

					'<form method="post">
						<fieldset>
							<div class="form-group grid-50">
								<label for="' . parent::$USERNAME_FIELD . '">Användarnamn</label>
								<input type="text" name="' . parent::$USERNAME_FIELD . '">
							</div>

							<div class="form-group grid-50">
								<label for="' . parent::$PASSWORD_FIELD . '">Lösenord</label>
								<input type="password" name="' . parent::$PASSWORD_FIELD . '">
							</div>
								
							<input type="submit" value="Logga in" class="btn btn-green prefix-20 grid-60 suffix-20" name="' . parent::$SUBMIT_BUTTON . '">
						</fieldset>
					</form>
				</section>
			</article>
		';

		//Return HTML.
		return $html;
	}

	/**
	 * Adds messages to the view.
	 * @return void
	 */
	public function loginFailed() {
		$bad_input = false;

		//If username is missing.
		if($this->getUsername() == "") {
			$this->addErrorMessage(self::$ERROR_USERNAME_MISSING);
			$bad_input = true;
		}

		//If password is missing.
		if($this->getPassword() == "") {
			$this->addErrorMessage(self::$ERROR_PASSWORD_MISSING);
			$bad_input = true;
		}

		//If none of the above.
		if(!$bad_input) {
			$this->addErrorMessage(self::$ERROR_MISSING_USER);
		}
	}
}