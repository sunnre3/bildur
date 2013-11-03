<?php

namespace image\view;

class Image {
	/**
	 * Returns HTML with all images that was passed
	 * as argument.
	 * @param  \image\model\Image[] $images
	 * @return string HTML
	 */
	public function getAll($images) {
		//HTML string.
		$html = '';

		//Loop through them all.
		foreach($images as $key => $image) {
			$id = $image->getId();
			$filepath = $image->getFilePath();
			$title = $image->getTitle();
			$caption = $image->getCaption();

			$html .= '
				<div class="post-image clearfix">
					<a href="' . ROUTER_PREFIX . ROUTER_SINGLE_IMAGE . '=' . $id . '" title="' . $title . '">
						<img src="' . $filepath . '" alt="' . $caption . '">
						<small>' . $caption . '</small>
					</a>
				</div>
		';
		}

		return $html;
	}
}