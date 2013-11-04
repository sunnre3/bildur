<?php

namespace common;

/**
 * THIS CLASS WILL BE USED THROUGHOUT
 * THE PROJECT IN ORDER TO REMOVE ANY BAD
 * INPUT THE USER MIGHT TRY AND SNEAK IN
 * BY REPLACING FOR EXAMPLE EVERY '<' '>'.
 */

class Filter {
	public static function hasTags($string) {
		$stripped = strip_tags($string);

		if($stripped != $string)
			return true;

		return false;
	}

	/**
	 * Takes a string that a user has submitted
	 * and returns  the same string but "sanitized"
	 * with every bad symbol removed or replaced.
	 * @param  string $string
	 * @return string
	 */
	public static function sanitize($string) {
		return htmlspecialchars(trim($string));
	}
}