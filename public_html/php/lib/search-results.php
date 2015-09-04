<div class="results">
	<div class="container">
		<?php

		if(isset($_SESSION["matchedRestaurants"])) {
			foreach($_SESSION["matchedRestaurants"] as $restaurant) {
				$name = $restaurant->getName();
				require($PREFIX . "php/lib/search-result.php");
			}
		} else {
			?>
			<div class="row">
				<div class="result">
					<div class="row">
						<div class="col-md-2">
							<img class="img-responsive center-block"
								 src="<?php echo $PREFIX; ?>images/trufork-logo/tr-Icons/trufork-lg.svg"/>
						</div>
						<div class="col-md-10">
							<h2 class="result-restaurant-name">No results!</h2>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
		?>
	</div>
</div>