<?php

//?>
<div class="container">
	<div class="row">
		<div class="col-md-3">
			<a class="home-link" href="<?php echo $PREFIX; ?>">
					<span class=" title">
						<img class="logo" src="<?php echo $PREFIX; ?>images/trufork-logo/tr-icons/trufork-lg.svg"
							 alt="TruFork Logo"/>
						TruFork
					</span>
			</a>
		</div>
		<div class="col-md-5">
			<form class="form-inline" id="main-search" method="get"
				  action="<?php echo $PREFIX ?>php/controllers/google-api-controller.php">
				<div class="input-group">
					<input id="userQuery" name="userQuery" type="text" class="form-control main-search input-lg"
						   placeholder="Search in Albuquerque">
						<span class="input-group-btn">
							<button id="restaurant-search-submit" type="submit" class="btn btn-lg main-search-button">
								<i class="fa fa-search"></i></button>
						</span>
				</div>
			</form>
		</div>
		<div class="col-md-4">
			<?php require_once("header-buttons.php");
			require_once(dirname(__DIR__) . "/controllers/sign-up-login-modal.php");
			require_once(dirname(__DIR__) . "/controllers/login-modal.php");
			?>
		</div>
	</div>
</div>