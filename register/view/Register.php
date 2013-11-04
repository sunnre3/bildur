<?php

namespace register\view;

require_once('./user/model/User.php');
require_once('./login/view/Login.php');

class Register extends \login\view\Login {
	private static $ERROR_USERNAME_MINLENGTH = 'Användarnamnet är för kort.';
	private static $ERROR_USERNAME_MAXLENGTH = 'Användarnamnet är för långt.';
	private static $ERROR_PASSWORD_MINLENGTH = 'Lösenordet är för kort.';
	private static $ERROR_PASSWORD_MAXLENGTH = 'Lösenordet är för långt.';
	private static $ERROR_PW_MISMATCH = 'Lösenorden matchar inte.';
	private static $ERROR_USERNAME_TAKEN ='Användarnamnet är upptaget.';

	/**
	 * Returns HTML containing the registration
	 * form along with some possible messages.
	 * @return string HTML
	 */
	public function getRegisterForm() {
		$message = "<div class=\"grid-container errors\"><ul>{$this->message}</ul></div>";

		//HTML string.
		$html = '
			<article id="register-page" class="post">
				<header class="post-header">
					<h1>Registrera mig</h1>
				</header>

				<p>Genom att registrera dig så kan du bli delaktig i den grymma 
				gemenskapen som finns här på <strong>bildur</strong>! Sluta lurka och sätt ett namn 
				på dina åsikter! Som registrerad medlem får du tillgång till att själv ladda upp bilder 
				samt att kommentera alla inlägg!<p>

				<p>Observera att fältet e-postadress är helt frivilligt och det enda din e-postadress kommer 
				att användas för är att hämta din gravatar! Vi lovar att inte skicka ett enda mejl.</p>

				<p>Välkommen på förhand!</p>

				<section id="register-form">'

					. $message .

					'<form method="post">
						<fieldset>
							<div class="form-group grid-container">
								<label class="grid-20" for="' . parent::$USERNAME_FIELD . '">Användarnamn <span class="required">*</span></label>
								<div class="grid-80">
									<input type="text" name="' . parent::$USERNAME_FIELD . '" id="' . parent::$USERNAME_FIELD . '">
								</div>
							</div>

							<div class="form-group grid-container">
								<label class="grid-20" for="' . parent::$EMAIL_FIELD . '">Email</label>
								<div class="grid-80">
									<input type="text" name="' . parent::$EMAIL_FIELD . '" id="' . parent::$EMAIL_FIELD . '">
								</div>
							</div>

							<div class="form-group grid-container">
								<label class="grid-20" for="' . parent::$PASSWORD_FIELD . '">Lösenord <span class="required">*</span></label>
								<div class="grid-80">
									<input type="password" name="' . parent::$PASSWORD_FIELD . '" id="' . parent::$PASSWORD_FIELD . '">
								</div>
							</div>

							<div class="form-group grid-container">
								<label class="grid-20" for="' . parent::$REPEAT_PASSWORD_FIELD . '">Upprepa lösenord <span class="required">*</span></label>
								<div class="grid-80">
									<input type="password" name="' . parent::$REPEAT_PASSWORD_FIELD . '" id="' . parent::$REPEAT_PASSWORD_FIELD . '">
								</div>
							</div>

							<div class="form-group grid-container">
								<div class="grid-80 prefix-20">
									<input type="submit" value="Registrera mig" class="btn btn-blue" name="' . parent::$SUBMIT_BUTTON . '">
								</div>
							</div>
						</fieldset>
					</form>
				</section>
			</article>';

		return $html;
	}

	/**
	 * When a user fails registrating,
	 * we should show the user why by
	 * leaving error messages.
	 * @return void
	 */
	public function registerFailure() {
		$USERNAME_MIN_LENGTH = \user\model\Username::MIN_LENGTH;
		$USERNAME_MAX_LENGTH = \user\model\Username::MAX_LENGTH;
		$PASSWORD_MIN_LENGTH = \user\model\Password::MIN_LENGTH;
		$PASSWORD_MAX_LENGTH = \user\model\Password::MAX_LENGTH;

		//Boolean.
		$input_error = false;

		//If username is shorter than what is allowed.
		if(strlen($this->getUsername()) < $USERNAME_MIN_LENGTH) {
			$this->addErrorMessage(self::$ERROR_USERNAME_MINLENGTH);
			$input_error = true;
		}

		//If username is longer than what is allowed.
		elseif(strlen($this->getUsername()) > $USERNAME_MAX_LENGTH) {
			$this->addErrorMessage(self::$ERROR_USERNAME_MAXLENGTH);
			$input_error = true;
		}

		//If password is shorter than what is allowed.
		if(strlen($this->getPassword()) < $PASSWORD_MIN_LENGTH) {
			$this->addErrorMessage(self::$ERROR_PASSWORD_MINLENGTH);
			$input_error = true;
		}

		//If password is longer than what is allowed.
		elseif(strlen($this->getPassword()) > $PASSWORD_MAX_LENGTH) {
			$this->addErrorMessage(self::$ERROR_PASSWORD_MAXLENGTH);
			$input_error = true;
		}

		//If the two passwords doesn't match.
		if($this->getRepeatedPassword() != $this->getPassword()) {
			$this->addErrorMessage(self::$ERROR_PW_MISMATCH);
			$input_error = true;
		}

		//If it's none of the above then the username is already taken.
		if(!$input_error)
			$this->addErrorMessage(self::$ERROR_USERNAME_TAKEN);
	}

	/**
	 * Private helper method for getting
	 * the email from $_POST.
	 * @return string
	 */
	private function getEmail() {
		if(isset($_POST[parent::$EMAIL_FIELD]))
			return $_POST[parent::$EMAIL_FIELD];

		return "";
	}

	/**
	 * Private helper method for getting
	 * the repeated password from $_POST.
	 * @return string password
	 */
	private function getRepeatedPassword() {
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
		return \user\model\User::__new($this->getUsername(),
									   $this->getEmail(),
									   $this->getPassword(),
									   $this->getRepeatedPassword());
	}
}