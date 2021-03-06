<?php
require_once($PREFIX . "php/classes/autoload.php");
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}
?>
<div class="results">
	<div class="container">
		<?php
		if(empty($_SESSION["matchedRestaurants"]) === false) {
			foreach($_SESSION["matchedRestaurants"] as $restaurant) {
				$id = $restaurant->getRestaurantId();
				$name = $restaurant->getName();
				$address = $restaurant->getAddress();
				$rating = $restaurant->getForkRating();
				require($PREFIX . "php/lib/search-result.php");
			}
			$_SESSION["matchedRestaurants"] = [];
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