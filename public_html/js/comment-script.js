/**
 * JS script to handle comments
 * jQuery interspersed
 **/

$(document).ready(function(){
	/**
	 * Once the DOM is loaded, the code executes
	 **/

	/**
	 * This should prevent multiple comment submits by impatient (l)users
	 **/
	var working = false;

	/**
	 * This listens for the submit event on the form, refers to CSS element
	 */
	$('.restaurant-comment-add-box').submit(function(e){

		e.preventDefault();
		if(working) return false;

		/**
		 * This should either submit the comment or remove if it was error.
		 * refers to CSS element
		 **/
		working = true;
		$('.restaurant-comment-add-button').val('Working...');
		$('span.error').remove();

		/**
		 *
		 * Gets current date and time...
		 * ...but doesn't do anything w it
		 * need to pass the result along w the actual comment to the class file
		 * in a way that complies w dateTime
		 **/
		function getActualFullDate() {
			var d = new Date();
			var year = addZero(d.getFullYear());
			var month = addZero(d.getMonth()+1);
			var day = addZero(d.getDate());
			var h = addZero(d.getHours());
			var m = addZero(d.getMinutes());
			var s = addZero(d.getSeconds());
			return year + ". " + month + ". " + day + " (" + h + ":" + m + ":" + s + ")";
		}

		$(document).ready(function() {
			$("#full").html(getActualFullDate());
		});

			/**
		 * This encodes the form content as string and sends to comment.php
		 * refers to CSS element
		 **/
		$.post('comment.php',$(this).serialize(),function(msg) {

			working = false;
			$('.restaurant-comment-add-button').val('Add');

			if(msg.status) {

				/**
				 * displays error messages if errors occurred
				 *
				 **/
				$.each(msg.errors, function(key, value) {
					$('label[for=' + key + ']').append('<span class="error">' +
						value + '</span>');
				});
			}
		});
		});
	});
