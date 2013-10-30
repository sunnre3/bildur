<?php

namespace application\view;

class AppView {
	/**
	 * Returns the action that is called
	 * based on the query string.
	 * @return string
	 */
	public function getAction() {
		return array(key($_GET) => reset($_GET));
	}
}