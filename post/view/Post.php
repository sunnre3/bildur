<?php

namespace post\view;

class Post {
	/**
	 * Array of posts.
	 * @var \post\model\Post[]
	 */
	private $posts;

	/**
	 * @param \post\model\Post[] $_posts
	 */
	public function __construct($_posts) {
		$this->posts = $_posts;
	}

	/**
	 * Returns a HTML string for all posts.
	 * @return [type] [description]
	 */
	public function getAll() {
		//HTML string.
		$html = "";

		//Loop through all posts to append HTML.
		foreach($this->posts as $key => $post) {
			$id = $post->getId();
			$title = $post->getTitle();
			$content = $post->getContent();

			$html .= "
			<article id='$id' class='post'>
				<header class='post-header'>
					<h1>$title</h1>
				</header>

				<p>$content</p>
			</article>";
		}

		//Return.
		return $html;
	}
}