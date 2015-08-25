<div class="container">
	<div class="row">
		<div class="col-md-3">
			<a class="home-link" href=".">
				<span class=" title">
				<img class="logo" src="<?php echo $PREFIX; ?>images/fork.svg" alt="TruFork Logo"/>
				TruFork
				</span>
			</a>
		</div>
		<div class="col-md-5">
			<form id="header-search" method="get" action="">
				<div class="inline-block search-bar-left">
					<input class="header-search" type="search" placeholder="Search in Albuquerque"/>
				</div>
				<div class="inline-block search-bar-right">
					<input class="header-search-button" type="submit" value="Search"/>
				</div>
			</form>
		</div>
		<div class="col-md-4">
			<ul class="nav nav-pills pull-right">

				<!--Button Trigger User Login--> 
				<li><button type="button" class="modal-button" data-toggle="modal" data-target="#myModal">Sign Up</button></li>
				<li><button type="button" class="modal-button" data-toggle="modal" data-target="#loginModal"> Login</button></li>

				<!-- 	login modal--> 
				<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
					<div class="modal-dialog"> 
						<form id="signUpForm" name="signUpForm" class="form" action="<?php echo $PREFIX?>php/controllers/sign-up-controller.php" method="post">

							<div class="modal-content">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
										aria-hidden="true">&times;</span>
								</button>
								<div class="modal-header">
									<h3>Create A New Account</h3>
								</div>

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
											<input type="checkbox" id="rememberMe[]" name="rememberMe" value="Remember Me"> Remember Me
										</label>
									</div>
								</div>

								<div class="form-group">
									<div class="modal-footer"
										<label>
											<button type="submit" class="modal-button" id="submitButton" name="submitButton">Create Account</button>
										</label>
									</div>
							</div>
							<div id="outputArea"></div>
							<script type="text/javascript" src="<?php echo $PREFIX; ?>php/controllers/sign-up-controller.js"></script>
						</form>

						<!-- 	login modal--> 
						<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
							<div class="modal-dialog"> 
								<form id="signUpForm" name="signUpForm" class="form" action="<?php echo $PREFIX?>php/controllers/sign-up-controller.php" method="post">

									<div class="modal-content">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
												aria-hidden="true">&times;</span>
										</button>
										<div class="modal-header">
											<h3>Create A New Account</h3>
										</div>

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
											<div class="checkbox">
												<label>
													<input type="checkbox" id="rememberMe[]" name="rememberMe" value="Remember Me"> Remember Me
												</label>
											</div>
										</div>

										<div class="form-group">
											<div class="modal-footer"
											<label>
												<button type="submit" class="modal-button" id="submitButton" name="submitButton">Create Account</button>
											</label>
										</div>
									</div>
									<div id="outputArea"></div>
									<script type="text/javascript" src="<?php echo $PREFIX; ?>php/controllers/sign-up-controller.js"></script>
								</form>



					</div>
				</div>



			</ul>
		</div>
	</div>
</div>