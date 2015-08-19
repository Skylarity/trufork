// document ready event
$(document).ready(
	// inner function for the ready() event
	function() {

		// tell the validator to validate this form
		// ???????need a CSS element as below????????????????
		$("#commentController").validate({
			// setup the formatting for the errors
			errorClass: "label-danger",
			errorLabelContainer: "#txtComment",
			wrapper: "li",

			// rules define what is good/bad input
			rules: {
				// each rule starts with the inputs name (NOT id)
				content: {
					maxlength: 1064,
					required: true
				}
			},

			// error messages to display to the end user
			messages: {
				content: {
					maxlength: "Comment is too long.",
					required: "What's on your mind?"
				}
			},

			// setup an AJAX call to submit the form without reloading
			submitHandler: function(form) {
				$(form).ajaxSubmit({
					// GET or POST
					type: "POST",
					// where to submit data
					url: "need path for a controller-post.php which doesn't presently exist",
					// this sends the XSRF token along with the form data
					headers: {
						"X-XSRF-TOKEN": Cookies.get("XSRF-TOKEN")
					},
					// success is an event that happens when the server replies
					// ???????????need to create the CSS elements below or rename to what is analogous???????
					success: function(ajaxOutput) {
						// clear the output area's formatting
						$("#txtComment").css("display", "");
						// write the server's reply to the output area
						$("#txtComment").html(ajaxOutput);


						// reset the form if it was successful
						// this makes it easier to reuse the form again
						if($(".alert-success").length >= 1) {
							$(form)[0].reset();
						}
					}
				});
			}
		});
	});