<?php

namespace output\model;

class Script {
	/**
	 * Filepath to script
	 * @var [type]
	 */
	public $url;

	/**
	 * If inline or seperate file
	 * @var boolean
	 */
	public $external;

	/**
	 * Script content
	 * @var string
	 */
	public $content;

	/**
	 * @param string  $script_url     URL to script file
	 * @param boolean $is_external    if script is external
	 * @param string  $script_content script content
	 */
	public function __construct(
		$_url = '',
		$_external = false,
		$_content = '') {

		$this->url = $_url;
		$this->external = $_external;
		$this->content = $_content;

	}
}