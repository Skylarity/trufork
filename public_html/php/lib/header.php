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
				<button type="button" class="header-search-button" data-toggle="modal" data-target="#myModal"> 
					Login or create an account
				</button> 


				<!-- 	login modal--> 
				<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
					<div class="modal-dialog"> 
						<div class="modal-content">
							<div class="modal-header">
								<div class="button" class="create-account" data dismiss="modal" 
									  aria-label="close"><span aria-hidden="true">&times;</span></button> 
									<h3 class="new-account">Modal title</h3></div>
								<div	class="sign-up-body"> 
									<input class="create" type="text" placeholder="choose a user name"/> 
									<input class="create" type="text" placeholder="password"/>
									<input class="create" type="text" placeholder="verify password"/> 
									<input class="create" type="text" placeholder="email (optional)"/> 
									<input class="create" type="checkbox"<span class="checkbox" Remember Me </span> 
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