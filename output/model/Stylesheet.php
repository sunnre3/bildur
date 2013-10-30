<?php

namespace output\model;

class Stylesheet {
	/**
	 * Publicly available identifier for this .css
	 * @var string
	 */
	public $identifier;

	/**
	 * Publicly available path to the .css file
	 * @var string
	 */
	public $filename;

	public function __construct($_identifier, $_filename) {
		$this->filename = $_filename;
	}
}