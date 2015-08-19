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
				<button type="button" class="modal-button" data-toggle="modal" data-target="#myModal"> 
					Sign Up
				</button>
				 
				<!-- 	login modal--> 
				<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
					<div class="modal-dialog"> 
						<form class="form">
							<div class="modal-content">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
										aria-hidden="true">&times;</span>
								</button>
								<div class="modal-header">
									<h3>Create A New Account</h3>
								</div>

								<div class="form-group">
									<label for="UserId">User Name</label>
									<input type="UserId" class="form-control" id="UserId" placeholder="Choose a username">
								</div>

								<div class="form-group">
									<label for="hash1">Password</label>
									<input type="password" class="form-control" id="verifyPassword1" placeholder="password">
								</div>

								<div class="form-group">
									<label for="Hash2">Email address</label>
									<input type="Hash2" class="form-control" id="verifyPassword1" placeholder="verify password">
								</div>

								<div class="form-group">
									<label for="email">Email address</label>
									<input type="email" class="form-control" id="email" placeholder="email (optional)">
								</div>

								<div class="form-group">
									<div class="checkbox">
										<label>
											<input type="checkbox"> Remember me
										</label>
									</div>
								</div>

								<div class="form-group">
									<div class="modal-footer"
										<label>
											<button type="submit" class="modal-button">Create Account</button>
										</label>
									</div>
							</div>
						</form>
					</div>
				</div>

				<!--Button Trigger User Login--> 
				<button type="button" class="modal-button" data-toggle="modal" data-target="#myModal"> 
					Log In
				</button>
				 
				<!-- 	login modal--> 
				<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
					<div class="modal-dialog"> 
						<form class="form">
							<div class="modal-content">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
										aria-hidden="true">&times;</span>
								</button>
								<div class="modal-header">
									<h3>Log In</h3>
								</div>

								<div class="form-group">
									<label for="UserId">User Name</label>
									<input type="UserId" class="form-control" id="UserId" placeholder="Choose a username">
								</div>

								<div class="form-group">
									<label for="hash1">Password</label>
									<input type="password" class="form-control" id="verifyPassword1" placeholder="password">
								</div>

								<div class="form-group">
									<div class="modal-footer"
									<label>
										<button type="submit" class="modal-button">Create Account</button>
									</label>
								</div>
							</div>
						</form>
					</div>
				</div>

				<!--				<li role="presentation"><a href="#">Login/Register</a></li>-->
				<!--				-<li role="presentation"><a href="epic/epic.php">Epic</a></li>-->
				<!--				<li role="presentation"><a href="epic/epic-addendum.php">Addendum</a></li>-->

			</ul>
		</div>
	</div>
</div>