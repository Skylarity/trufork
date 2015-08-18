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
					Login or create an account
				</button> 


				<!-- 	login modal--> 
				<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
					<div class="modal-dialog"> 

						<div class="modal-content">
								<div class="button"  data dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button> 

									<form class="form-horizontal">
										<div class="modal-header">
											<h3>Create A New Account</h3>

										<div class="form-group">
											<label for="inputUserName" class="col-sm-2">User Name</label>
											<div class="col-sm-10">
												<input type="User Name" class="form-control" id="User Name" placeholder="User Name">
											</div>
										</div>

										<div class="form-group">
											<label for="inputPassword" class="col-sm-2 control-label">Password</label>
											<div class="col-sm-10">
												<input type="password" class="form-control" id="inputPassword3" placeholder="Password">
											</div>
										</div>

										<div class="form-group">
											<label for="inputVerifyPassword" class="col-sm-2 control-label">Verify Password</label>
											<div class="col-sm-10">
												<input type="password" class="form-control" id="inputPassword3" placeholder="Verify Password">
											</div>
										</div>

										<div class="form-group">
											<label for="email" class="col-sm-2 control-label">email (optional)</label>
											<div class="col-sm-10">
												<input type="email" class="form-control" id="inputPassword3" placeholder="Email (Optional)">
											</div>
										</div>

										<div class="form-group">
											<div class="col-sm-offset-2 col-sm-10">
													<label>
														<button type="submit" class="modal-button">Create Account</button>
													</label>
												</div>
											</div>
										</div>


										<div class="form-group">
											<div class="col-sm-offset-2 col-sm-10">
												<div class="checkbox">
													<label>
														<input type="checkbox"> Remember me
													</label>
												</div>
											</div>
										</div>

										<div class="form-group">
											<div class="col-sm-offset-2 col-sm-10">
												<button type="submit" class="modal-button">Sign in</button>
											</div>
										</div>
									</form>

<!--									<input class="create" type="text" placeholder="password"/>-->
<!--									<input class="create" type="text" placeholder="verify password"/> -->
<!--									<input class="create" type="text" placeholder="email (optional)"/> -->
<!--									<input class="create" type="checkbox"<span class="checkbox" Remember Me </span> -->

								</form>
							</div> 
						</div> 
					</div> 
				</div> 
<!--				<li role="presentation"><a href="#">Login/Register</a></li>-->
				<!--				-<li role="presentation"><a href="epic/epic.php">Epic</a></li>-->

<!--				<li role="presentation"><a href="epic/epic-addendum.php">Addendum</a></li>-->
			</ul>
		</div>
	</div>