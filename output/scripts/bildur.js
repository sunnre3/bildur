//Make sure the document is ready.
//Then load all the query string.
var QUERYSTRING;
(window.onpopstate = function () {
    var match,
        pl     = /\+/g,  // Regex for replacing addition symbol with a space
        search = /([^&=]+)=?([^&]*)/g,
        decode = function (s) { return decodeURIComponent(s.replace(pl, " ")); },
        query  = window.location.search.substring(1);

    QUERYSTRING = {};
    while (match = search.exec(query))
       QUERYSTRING[decode(match[1])] = decode(match[2]);
})();

$(document).ready(function() {
	//Make sure we are on a single post view.
	if(QUERYSTRING['postID']) {

		//When the user wants to edit a comment.
		$('div.comment-actions a[data-ac=edit-comment]').on('click', function(e) {
			//Prevent default browser
			//behaviour.
			e.preventDefault();

			editComment(this);
		});

		$('div.comment-actions a[data-ac=delete-comment]').on('click', function(e) {
			//Prevent default browser
			//behaviour.
			e.preventDefault();

			deleteComment(this);
		});

		function editComment(obj) {
			//Set up the commentContainer.
			var commentContainer = obj.parentNode.parentNode.parentNode;

			//Flush the div.comment-actions.
			var commentActions = $('div.comment-actions', commentContainer);
			$(commentActions).empty();

			//Add new actions.
			var saveComment = $('<a href="#" class="comment-btn" data-ac="save-comment">Spara</a>');
			var revertComment = $('<a href="#" class="comment-btn" data-ac="revert-comment">Ångra</a>');
			$(commentActions).append(saveComment).append(revertComment);

			//Save the old comment.
			var oldComment = $('p', commentContainer).text();
			
			//Remove the comment.
			$('p', commentContainer).remove();

			//Add an input field.
			var newComment = $('<textarea placeholder="Din kommentar" name="comment_body">' + oldComment + '</textarea>');
			$(commentContainer).append(newComment);

			//Bind events.
			$(saveComment).on('click', function(e) {
				//Prevent default browser behavior.
				e.preventDefault();

				//Local variable.
				var commentID = parseInt($(commentContainer).data('id'));
				var commentBody = $('textarea', commentContainer).val();

				//Check if the comment isn't empty.
				if(commentBody.length > 0) {
					var post = {
						'commentID': commentID,
						'postID': QUERYSTRING['postID'],
						'content': commentBody
					};

					//Make the AJAX call.
					$.ajax({
						url: 'ajax.php',
						data: {
							action: 'editComment',
							comment: JSON.stringify(post)
						},
						type: 'post',
						success: function() {
							//On success we reload the page.
							location.reload();
						}
					});
				}
			});

			$(revertComment).on('click', function(e) {
				//Prevent default browser behavior.
				e.preventDefault();

				//Remove current actions.
				$(commentActions).empty();

				//Add new ones.
				$(commentActions)
					.append('<a class="comment-btn" data-ac="edit-comment" href="#">Redigera</a>')
					.append('<a class="comment-btn" data-ac="delete-comment" href="#">Ta bort</a>');

				//Remove the textarea.
				$('textarea', commentContainer).remove();

				//Add the old comment.
				$(commentContainer).append('<p>' + oldComment + '</p>');
			});
		}

		function deleteComment(obj) {
			//Set up the commentContainer.
			var commentContainer = obj.parentNode.parentNode.parentNode;

			//Local variable.
			var commentID = parseInt($(commentContainer).data('id'));

			console.log(commentContainer);

			$.ajax({
				url: 'ajax.php',
				data: {
					action: 'deleteComment',
					commentID: commentID
				},
				type: 'post',
				success: function() {
					location.reload();
				}
			});
		}

	}
});