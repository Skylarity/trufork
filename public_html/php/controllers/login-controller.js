// document ready event
$(document).ready(
	// inner function for the ready() event
	function() {

		// tell the validator to validate this form
		$("#login-controller").validate({
			debug: true,
			// setup the formatting for the errors
			errorClass: "label-danger",
			errorLabelContainer: "#outputArea-login",
			wrapper: "li",

			// rules define what is good/bad input
			rules: {
				// each rule starts with the inputs name (NOT id)
				loginEmail: {
					minlength: 1,
					required: true
				},

				loginPassword: {
					minlength: 8,
					required: true
				}
				},


				// error messages to display to the end user
				messages: {
					loginEmail: {
						minlength: "user name must be positive",
						required: "must enter a valid email"
					},

					loginPassword: {
						minlength: "please enter 8 characters",
						required: "please enter valid password"
					}
				},


				// setup an AJAX call to submit the form without reloading
				submitHandler: function(form) {
					$("#login-controller").ajaxSubmit({
						// GET or POST
						type: "POST",
						// where to submit data
						url: $("#login-controller").attr("action"),
						// this sends the XSRF token along with the form data
						headers: {
							"X-XSRF-TOKEN": Cookies.get("XSRF-TOKEN")
						},
						// success is an event that happens when the server replies
						success: function(ajaxOutput) {
							// clear the output area's formatting
							$("#outputArea-login").css("display", "");
							// write the server's reply to the output area
							$("#outputArea-login").html(ajaxOutput);


							// reset the form if it was successful
							// this makes it easier to reuse the form again
							if($(".alert-success").length > 0) {
								$("#login-controller")[0].reset();

								//refresh page on successful login
								setTimeout(function() {location.reload(true);}, 1000);
							}
						}
					});

					$("#submitButton").click(function() {
						$("#login-controller").modal("hide");
					});
				}
			});
		});