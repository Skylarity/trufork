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
			<form class="form-inline" id="header-search" method="get" action="">
				<div class="input-group">
					<input class="header-search form-control" type="search" placeholder="Search in Albuquerque"/>
						<span class="input-group-btn">
							<button class="btn header-search-button" type="submit">Search</button>
						</span>
				</div>
			</form>
		</div>
		<div class="col-md-4">
			<ul class="nav nav-pills pull-right">
				<!--Button Trigger User Login--> 
				<li><button type="button" class="modal-button btn-lg" data-toggle="modal" data-target="#signup"> Sign Up</button></li>
				<li><button type="button" class="modal-button btn-lg" data-toggle="modal" data-target="#login">Log In</button></li>
			</ul>
		</div>
	</div>
</div>