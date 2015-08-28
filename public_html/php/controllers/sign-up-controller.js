// document ready event
$(document).ready(
	// inner function for the ready() event
	function() {

		// tell the validator to validate this form
		$("#sign-up-form").validate({
			debug: true,
			// setup the formatting for the errors
			errorClass: "has-error",
			errorLabelContainer: "#outputArea",
			wrapper: "li",

			// rules define what is good/bad input
			rules: {
				// each rule starts with the inputs name (NOT id)
				userName: {
					minlength: 1,
					required: true
				},

				password: {
					minlength: 8,
					required: true
				},

				verifyPassword: {
					equalTo: "#password",
					required: true
				},

				email: {
					required: true,
					email: true
				}
			},

			// error messages to display to the end user
			messages: {
				userName: {
					minlength: "user name must be positive",
					required: "must enter a valid user name"
				},

				password: {
					minlength: "please enter 8 characters",
					required: "please enter valid password"
				},

				verifyPassword: {
					equalTo: "passwords do not match",
					required: "password do not match"
				},

				email: {
					required: "must enter email",
					email: "please enter valid email"
				}
			},

			// setup an AJAX call to submit the form without reloading
			submitHandler: function(form) {
				$("#sign-up-form").ajaxSubmit({
					// GET or POST
					type: "POST",
					// where to submit data
					url: $("#sign-up-form").attr("action"),
					// this sends the XSRF token along with the form data
					headers: {
						"X-XSRF-TOKEN": Cookies.get("XSRF-TOKEN")
					},
					// success is an event that happens when the server replies
					success: function(ajaxOutput) {
						// clear the output area's formatting
						$("#outputArea").css("display", "");
						// write the server's reply to the output area
						$("#outputArea").html(ajaxOutput);


						// reset the form if it was successful
						// this makes it easier to reuse the form again
						if($(".alert-success").length > 0) {
							$("#sign-up-form")[0].reset();
						}
					}
				});

					$("#submitButton").click(function() {
					$("#signup").modal("hide");
				});
			}
		});
	});