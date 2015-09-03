<div class="container">
	<div class="row">
		<div class="col-md-12">
			<h1 class="search-main-h1">Find a delicious, clean place to eat.</h1>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<form id="main-search" method="get" action="../controllers/global-text-search-controller.php">
				<div class="main-search-align">
					<div class="input-group">
						<input id="mainSearch" name="mainSearch" type="search" class="form-control main-search input-lg" placeholder="Search in Albuquerque">
						<span class="input-group-btn">
							<button id="restaurant-search-submit" type="submit" class="btn btn-lg main-search-button"><i class="fa fa-search"></i></button>
						</span>
					</div>
				</div>
			</form>
			<div id="outputArea"></div> <!--call this something else?????-->
			<script type="text/javascript" src= "<?php echo $PREFIX; ?>php/controllers/global-text-search-controller.js"></script>
		</div>
	</div>
</div>
