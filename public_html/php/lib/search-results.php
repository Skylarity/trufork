<?php
require_once($PREFIX . "php/classes/restaurant.php");
?>
<div class="results">
	<div class="container">
		<?php

		if(isset($_SESSION["matchedRestaurants"])) {
			foreach($_SESSION["matchedRestaurants"] as $restaurant) {
				$id = $restaurant->getRestaurantId();
				$name = $restaurant->getName();
				require($PREFIX . "php/lib/search-result.php");
			}
		} else {
			?>
			<div class="row">
				<div class="no-result">
					<div class="row">
						<div class="col-md-12">
							<h2 class="result-restaurant-name"><i class="fa fa-frown-o"></i> No results!</h2>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
		?>
	</div>
</div>