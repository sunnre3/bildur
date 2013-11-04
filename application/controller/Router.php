<?php

namespace application\model;

require_once('./application/view/AppView.php');
require_once('./output/controller/NewPost.php');
require_once('./output/controller/EditPost.php');
require_once('./output/controller/DeletePost.php');
require_once('./output/controller/Login.php');
require_once('./output/controller/Logout.php');
require_once('./output/controller/Register.php');
require_once('./output/controller/FrontPage.php');
require_once('./output/controller/Single.php');

class Router {
	/**
	 * @var \application\view\AppView
	 */
	private $view;

	/**
	 * Initiate object
	 */
	public function __construct() {
		$this->view = new \application\view\AppView();
	}

	/**
	 * Determines which controller should be run.
	 * @return object
	 */
	public function getController() {
		switch($this->view->getAction()) {
			case ROUTER_NEW_POST:
				return new \output\controller\NewPost();
				break;

			case ROUTER_EDIT_POST:
				return new \output\controller\EditPost();
				break;

			case ROUTER_DELETE_POST:
				return new \output\controller\DeletePost();
				break;

			case ROUTER_LOGIN:
				return new \output\controller\Login();
				break;

			case ROUTER_LOGOUT:
				return new \output\Controller\Logout();
				break;

			case ROUTER_REGISTER:
				return new \output\controller\Register();
				break;

			case ROUTER_SINGLE_POST:
				return new \output\controller\Single();
				break;
			
			default:
				return new \output\controller\FrontPage();
				break;
		}
	}
}