<!-- login modal -->
<div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="login modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h3 class="modal-title" id="login">Log In</h3>
			</div> 
			<div class="modal-body">
				<form id="login-controller" class="form" method="post" action="<?php echo $PREFIX?>php/controllers/login-controller.php">
					<div class="form-group">
						<label for="loginEmail">email</label>
						<input type="text" class="form-control" id="loginName"" placeholder="Email">
					</div>
					<div class="form-group">
						<label for="loginPassword">Password</label>
						<input type="password" class="form-control" id="loginPassword" placeholder="password">
					</div>
					<div class="form-group">
						<button type="submit" class="btn modal-button">Login</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
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
						<label for="userName">User Name</label>
						<input type="text" class="form-control" id="userName" name="userName" placeholder="choose a username">
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