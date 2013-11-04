<?php

namespace login\view;

require_once('./common/view/BasicView.php');
require_once('./common/model/Observer.php');

class Login extends \common\view\BasicView implements \common\model\Observer {
	private static $ERROR_USERNAME_MISSING = 'Användarnamn saknas.';
	private static $ERROR_PASSWORD_MISSING = 'Lösenord saknas.';
	private static $ERROR_MISSING_USER = 'Felaktig kombination av användarnamn/lösenord.';

	private static $COOKIE_USERNAME = 'BILDUR::username';
	private static $COOKIE_PASSWORD = 'BILDUR::password';

	/**
	 * Private helper method for getting
	 * username from either $_POST or $_COOKIE.
	 * @return string username
	 */
	protected function getUsername() {
		if(isset($_POST[parent::$USERNAME_FIELD]))
			return $_POST[parent::$USERNAME_FIELD];

		elseif(isset($_COOKIE[self::$COOKIE_USERNAME]))
			return $_COOKIE[self::$COOKIE_USERNAME];

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

		elseif(isset($_COOKIE[self::$COOKIE_PASSWORD]))
			return $_COOKIE[self::$COOKIE_PASSWORD];

		return "";
	}

	/**
	 * Private helper method for checking
	 * if the user wants to be remembered
	 * with cookie data.
	 * @return boolean
	 */
	private function userWantsToBeRemembered() {
		return isset($_POST[parent::$REMEMBER_ME]);
	}

	/**
	 * Returns a User object based on
	 * user input.
	 * @return \user\model\User
	 */
	public function getUser() {
		if($this->hasSavedLoginData())
			return \user\model\User::__saved($this->getUsername(),
											 $this->getPassword());

		else
			return \user\model\User::__login($this->getUsername(),
											 $this->getPassword());
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
								<input type="text" name="' . parent::$USERNAME_FIELD . '" id="' . parent::$USERNAME_FIELD . '">
							</div>

							<div class="form-group grid-50">
								<label for="' . parent::$PASSWORD_FIELD . '">Lösenord</label>
								<input type="password" name="' . parent::$PASSWORD_FIELD . '" id="' . parent::$PASSWORD_FIELD . '">
							</div>

							<div class="form-group-centered">
								<input type="checkbox" name="' . parent::$REMEMBER_ME . '" id="' . parent::$REMEMBER_ME . '" unchecked>
								<label for="' . parent::$REMEMBER_ME . '">Kom ihåg mig</label>
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

	public function hasSavedLoginData() {
		return isset($_COOKIE[self::$COOKIE_USERNAME]) &&
			   isset($_COOKIE[self::$COOKIE_PASSWORD]);
	}

	/**
	 * Removes any login data that might
	 * be saved on the client.
	 * @return void
	 */
	public function removeCookies() {
		if(isset($_COOKIE[self::$COOKIE_USERNAME]))
			setcookie(self::$COOKIE_USERNAME, '', -3600);

		if(isset($_COOKIE[self::$COOKIE_PASSWORD]))
			setcookie(self::$COOKIE_PASSWORD, '', -3600);
	}

	/**
	 * From LoginObserver interface.
	 * When a user successfully login we can
	 * then proceed to check if the user wanted
	 * to be remembered and then save cookie data
	 * this their browser.
	 * @param  \user\model\User $user
	 * @return void
	 */
	public function notify(\user\model\User $user) {
		if($this->userWantsToBeRemembered()) {
			//Get the temporary password string.
			$tmpPasswordString = $user->getTmpPassword();

			//Rebuild a temporary password object.
			$temporaryPassword = \user\model\temporaryPassword::__enc($tmpPasswordString);

			//Set username cookie.
			setcookie(self::$COOKIE_USERNAME, $user->getUsername(), $temporaryPassword->expireDate);

			//Set password cookie.
			setcookie(self::$COOKIE_PASSWORD, $temporaryPassword->__toString(), $temporaryPassword->expireDate);
		}
	}
}