// document ready event
$(document).ready(
	// inner function for the ready() event
	function() {

		// tell the validator to validate this form
		$("#restaurant-comment-form").validate({
			//debug: true,
			// setup the formatting for the errors
			errorClass: "has-error",
			errorLabelContainer: "#outputArea",
			wrapper: "li",

			// rules define what is good/bad input
			rules: {
				// each rule starts with the inputs name (NOT id)
				txtComment: {
					maxlength: 1064,
					required: true
				}
			},

			// error messages to display to the end user
			messages: {
				txtComment: {
					maxlength: "Comment is too long.",
					required: "Please submit a comment."
				}
			},

			// setup an AJAX call to submit the form without reloading
			submitHandler: function(form) {
				$("#restaurant-comment-form").ajaxSubmit({
					// GET or POST
					type: "POST",
					// where to submit data
					url: $("#restaurant-comment-form").attr("action"),
					// this sends the XSRF token along with the form data
					headers: {
						"X-XSRF-TOKEN": Cookies.get("XSRF-TOKEN")
					},
					// success is an event that happens when the server replies
					success: function(ajaxOutput) {
						// clear the output area's formatting
						$("#CommentOutputArea").css("display", "");
						// write the server's reply to the output area
						$("#CommentOutputArea").html(ajaxOutput);


						// reset the form if it was successful
						// this makes it easier to reuse the form again
						if($(".alert-success").length >= 1) {
							$("#restaurant-comment-form")[0].reset();
						}
					}
				});
			}
		});
	});