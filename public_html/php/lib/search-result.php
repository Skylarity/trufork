<div class="row">
	<a href="<?php echo $PREFIX ?>/restaurant/?restaurantId=<?php echo $id; ?>">
		<div class="result">
			<div class="row">
				<div class="col-md-12">
					<h2 class="result-restaurant-name"><?php echo $name; ?></h2>

					<p class="result-restaurant-address"><?php echo $address; ?></p>

					<div class="result-restaurant-trufork-rating">
						<?php
						$rating = intval($rating);

						for($i = 0; $i < $rating; $i++) {
							?>
							<div class="star"><i class="fa fa-star"></i></div>
							<?php
						}
						for($i = 0; $i < 5 - $rating; $i++) {
							?>
							<div class="star"><i class="fa fa-star-o"></i></div>
							<?php
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</a>
</div>