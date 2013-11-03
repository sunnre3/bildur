<?php

namespace output\view;

require_once('./login/model/Login.php');
require_once('./login/model/LoginInfo.php');

class HTMLPage {
	/**
	 * Page title
	 * @var string
	 */
	private $page_title;

	/**
	 * Array containing all css files
	 * @var \common\model\Stylesheet[]
	 */
	private $stylesheet_array;

	/**
	 * Array containing script files
	 * @var \common\model\Script[]
	 */
	private $script_array;

	/**
	 * LoginModel
	 * @var \login\model\Login
	 */
	private $loginModel;

	/**
	 * @param \common\model\Stylesheet[] $stylesheets
	 * @param \common\model\Script[] 	 $scripts
	 */
	public function __construct() {
		//Add the standard styles and scripts.
		$this->standard_stylesheet();

		//Initiate loginModel;
		$this->loginModel = new \login\model\Login();
	}

	/**
	 * This method renders a page.
	 * @param  string $content
	 * @return void
	 */
	public function renderPage($content) {
		echo $this->getHeader() . $content . $this->getFooter();
	}

	/**
	 * Adds standard necessary css
	 * @return void
	 */
	private function standard_stylesheet() {
		$this->add_stylesheet('reset', 'reset.css');
		$this->add_stylesheet('fonts', 'fonts.css');
		$this->add_stylesheet('basic', 'basic.css');
		$this->add_stylesheet('default', 'default.css');
		$this->add_stylesheet('grid', 'unsemantic-grid-responsive.css');
	}

	/**
	 * Add a .css file
	 * @param  string $filepath
	 * @return void
	 */
	private function add_stylesheet($identifier, $filename) {
		if(isset($this->stylesheet_array[$identifier]))
			throw new \Exception('add_stylesheet failed: need unique identifier');

		$this->stylesheet_array[$identifier] = new \output\model\Stylesheet($identifier, $filename);
	}

	/**
	 * Add a script
	 * @param  string  $script_url
	 * @param  string  $script_type
	 * @param  boolean $is_external
	 * @param  string  $script_content
	 * @return void
	 */
	private function add_script(
		$script_url = '',
		$is_external = true,
		$script_content = '') {

		$this->script_array[] = new \output\model\Script(
			$script_url,
			$is_external,
			$script_content);

	}

	/**
	 * Public function to get HTML header
	 * @return string HTML
	 */
	private function getHeader() {

		$css = "";

		if(!empty($this->stylesheet_array)) {
			foreach ($this->stylesheet_array as $key => $stylesheet) {
				$css .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"".STYLESHEET_PATH."$stylesheet->filename\">\n\t\t";
			}
		}

		if($this->loginModel->isLoggedIn()) {
			$topbar = '
				<a class="nav-btn" href="' . ROUTER_PREFIX . ROUTER_LOGOUT . '">Logga ut</a>
				<a class="nav-btn" href="' . ROUTER_PREFIX . ROUTER_NEW_POST . '">Skapa nytt inlägg</a>';
		}

		else {
			$topbar ='
				<a class="nav-btn" href="' . ROUTER_PREFIX . ROUTER_LOGIN . '">Logga in</a>
				<a class="nav-btn" href="' . ROUTER_PREFIX . ROUTER_REGISTER . '">Registrera</a>';
		}

		$str = "<!DOCTYPE html>
<html>
	<head>
		<title>{$this->page_title}</title>
		<meta http-equiv=\"Content-Type\" content=\"text/html;charset=utf-8\">
		$css
	</head>

	<body>
		<div id=\"top-bar\">
			{$topbar}
		</div>
		<div id=\"wrapper\" class=\"grid-container\">
			<header id=\"main-header\">
				<a href=\"./\"><h1>BILDUR</h1></a>
			</header>";

		return $str;
	}

	/**
	 * Public function to get HTML footer
	 * @return string HTML
	 */
	private function getFooter() {
		$scripts = "";

		if(!empty($this->script_array)) {
			foreach ($this->script_array as $key => $script) {
				$scripts .= "<script type=\"text/javascript\"";
				$scripts .= ($script->isInline) ? ">$script->content" : " src=\"$script->src\">";
				$scripts .= "</script>\n";
			}
		}

		return "
		</div>
		$scripts
	</body>
</html>";
	}
}