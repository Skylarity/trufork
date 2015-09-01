
<!-- sign up modal --> 
<div class="modal fade" id="signup" tabindex="-1" role="dialog" aria-labelledby="signup">
	<div class="modal-dialog"> 
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h3 class="modal-title" id="sign-up">Create A New Account</h3>
			</div>
			<div class="modal-body">
				<form id="sign-up-form" name="sign-up-form" class="form" action="<?php echo $PREFIX?>php/controllers/sign-up-controller.php" method="post">
					<div class="form-group">
						<label for="name">Name</label>
						<input type="text" class="form-control" id="name" name="name" placeholder="choose a user name">
					</div>
					<div class="form-group">
						<label for=password>Password</label>
						<input type="password" class="form-control" id="password" name="password" placeholder="password">
					</div>
					<div class="form-group">
						<label for="verifyPassword">Verify Password</label>
						<input type="password" class="form-control" id="verifyPassword" name="verifyPassword" placeholder="verify password">
					</div>
					<div class="form-group">
						<label for="email">Email Address</label>
						<input type="email" class="form-control" id="email" name="email" placeholder="email">
					</div>
					<div class="form-group">
						<div class="checkbox">
							<label>
								<input type="checkbox" id="rememberMe[]" name="rememberMe" value="Remember Me">Remember Me
							</label>
						</div>
					</div>
					<div class="form-group">
						<button type="submit" class="btn modal-button" id="submitButton" name="submitButton">Create Account</button>
<!--						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
					</div>
				</form>
				<div id="outputArea"></div>
			</div>
		</div>
	</div>
</div>