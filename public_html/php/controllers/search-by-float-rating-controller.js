// document ready event
$(document).ready(
	// inner function for the ready() event
	function() {

		// tell the validator to validate this form
		$("#search-by-float-rating-form").validate({
			debug: true,
			// setup the formatting for the errors
			errorClass: "has-error",
			errorLabelContainer: "#outputArea",
			wrapper: "li",

			// setup an AJAX call to submit the form without reloading
			//not sure if this is necessary since there's no unique user input
			submitHandler: function(form) {
				$(form).ajaxSubmit({
					// GET or POST
					type: "POST",
					// where to submit data
					url: $(form).attr("action"),
					// this sends the XSRF token along with the form data, not sure if needed
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
						if($(".alert-success").length >= 1) {
							$(form)[0].reset();
						}
					}
				});
			}
		});
	});