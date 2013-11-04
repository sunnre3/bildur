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

			$html .= '
				<div class="post-image clearfix">
					<a href="' . $filepath . '">
						<img src="' . $filepath . '" alt="">
					</a>
				</div>
		';
		}

		return $html;
	}
}