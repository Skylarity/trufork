<?php
require_once(dirname(__DIR__) . "/controllers/sign-up-login-modal.php");
require_once(dirname(__DIR__) . "/controllers/login-modal.php");
?>
<div class="container">
	<div class="row">
		<div class="col-md-3">
			<a class="home-link" href="<?php echo $PREFIX; ?>">
					<span class=" title">
						<img class="logo" src="<?php echo $PREFIX; ?>images/trufork-logo/tr-icons/trufork-lg.svg"
							 alt="TruFork Logo"/>
						<div class="logo-text">TruFork</div>
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
			<?php require_once("header-buttons.php"); ?>
		</div>
	</div>
</div>