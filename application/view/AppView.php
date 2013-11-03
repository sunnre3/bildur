<?php

namespace application\view;

class AppView {
	/**
	 * Returns the action that is called
	 * based on the query string.
	 * @return string
	 */
	public function getAction() {
		return key($_GET);
	}

	/**
	 * Redirects the user to the front-page.
	 * @return void
	 */
	public function redirectToFrontPage() {
		header('Location: ./');
	}

	/**
	 * Redirects the user to a certain post.
	 * @param  \post\model\Post $post
	 * @return void
	 */
	public function redirectToPost(\post\model\Post $post) {
		header('Location: ' . ROUTER_PREFIX . ROUTER_SINGLE_POST . ROUTER_INFIX . $post->getId());
	}
}