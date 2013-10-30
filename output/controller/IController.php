<?php

namespace output\controller;

/**
 * By having all output controls implement this interface
 * I can make sure that we can run it from the ApplicationController.
 *
 * The First method, run(), sets up the controller in case there's
 * something we need for that particular control. This could be
 * fetching posts or comments from a DAL class for example.
 *
 * The second method, getContent(), returns a HTML string from
 * the correct view class. For example FrontPage controller
 * returns a string containing all posts HTML formatted.
 */

interface IController {
	public function run();
	public function getContent();
}