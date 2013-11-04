<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('html_errors', 1);

/**
 * THE PURPOSE OF THIS FILE IS TO
 * EXECUTE THE PROPER METHODS WHEN
 * WE RECIEVE A AJAX CALL.
 *
 * EVERY AJAX CALL MADE BY THE FRONT-END
 * MUST USE POST AND HAVE THE PARAMTER
 * ACTION.
 *
 * TO ADD AJAX FUNCTIONALITY YOU NEED TO
 * ADD YOUR CUSTOM "ACTION" TO THE IF STATEMENT.
 */

require_once('./database/config.php');
require_once('./comment/model/Comment.php');
require_once('./comment/model/CommentModel.php');
require_once('./login/model/Login.php');

class AJAX_Command {
	/**
	 * The POST parameter which we will
	 * operate on.
	 * @var string
	 */
	private static $AJAX_ACTION = 'action';

	public static function execute() {
		if(isset($_POST[self::$AJAX_ACTION]) && !empty($_POST[self::$AJAX_ACTION])) {
			//Local variable.
			$action = $_POST[self::$AJAX_ACTION];

			echo $action;

			//if 'editComment'...
			if($action == 'editComment') {
				//Make sure the JS script sent along a comment.
				if(isset($_POST['comment']) && !empty($_POST['comment'])) {
					//Start a session so we can get the 
					//currently logged in user.
					session_start();

					//Set default timezone.
					date_default_timezone_set('Europe/Stockholm');

					//Parse the data containing the post id to which
					//the comment belongs to along with the actual comment.
					$json_comment = json_decode($_POST['comment']);

					//We still need to add datetime and user id.
					$datetime = date('Y-m-d H:i:s');

					//Get a Comment object.
					$comment = new \comment\model\Comment($json_comment->commentID,
														  $json_comment->postID,
														  1, //Fake user_id since we don't really need one.
														  $datetime,
														  $json_comment->content);

					//Initiate a commentModel object.
					$commentModel = new \comment\model\CommentModel();

					//Update the comment.
					$commentModel->editComment($comment);
				}
			}

			//if 'deleteComment'...
			if($action == 'deleteComment') {
				//Make sure that the JS sent along a commentID.
				if(isset($_POST['commentID']) && !empty($_POST['commentID'])) {
					//Get the commentId.
					$commentId = $_POST['commentID'];

					//Get a CommentModel.
					$commentModel = new \comment\model\CommentModel();

					//Delete the comment.
					$commentModel->deleteCommentById($commentId);
				}
			}
		}
	}
}

AJAX_Command::execute();