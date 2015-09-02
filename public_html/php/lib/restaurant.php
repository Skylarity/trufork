<?php
require_once(dirname(__DIR__) . "classes/comment.php");
require_once(dirname(__DIR__) . "classes/user.php");
require_once("/etc/apache2/data-design/encrypted-config.php");
?>

<div class="restaurant">
	<div class="container">
		<div class="row">
			<div class="col-md-7">
				<div class="row">
					<div class="col-md-4">
						<img class="restaurant-image img-responsive" src="<?php echo $PREFIX ?>images/fork.svg"
							 alt="Restaurant Image"/>
					</div>
					<div class="col-md-8">
						<h1 class="restaurant-name">
							Restaurant Name
						</h1>

						<p class="restaurant-description">
							Restaurant description - Shields up. I recommend we transfer power to phasers and arm the
							photon torpedoes. Something strange on the detector circuit. The weapons must have disrupted
							our communicators. You saw something as tasty as meat, but inorganically materialized out of
							patterns used by our transporters. Captain, the most elementary and valuable statement in
							science, the beginning of wisdom, is 'I do not know.' All transporters off.
						</p>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="restaurant-rating">
							<div class="star"><i class="fa fa-star"></i></div>
							<div class="star"><i class="fa fa-star"></i></div>
							<div class="star"><i class="fa fa-star"></i></div>
							<div class="star"><i class="fa fa-star-o"></i></div>
							<div class="star"><i class="fa fa-star-o"></i></div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<h2 class="restaurant-inspection-h2">
							Inspection Information
						</h2>

						<p class="restaurant-inspection-p">
							Shields up. I recommend we transfer power to phasers and arm the photon torpedoes. Something
							strange on the detector circuit. The weapons must have disrupted our communicators. You saw
							something as tasty as meat, but inorganically materialized out of patterns used by our
							transporters. Captain, the most elementary and valuable statement in science, the beginning
							of wisdom, is 'I do not know.' All transporters off.
						</p>

						<p class="restaurant-inspection-p">
							We're acquainted with the wormhole phenomenon, but this... Is a remarkable piece of
							bio-electronic engineering by which I see much of the EM spectrum ranging from heat and
							infrared through radio waves, et cetera, and forgive me if I've said and listened to this a
							thousand times. This planet's interior heat provides an abundance of geothermal energy. We
							need to neutralize the homing signal.
						</p>

						<p class="restaurant-inspection-p">
							Unidentified vessel travelling at sub warp speed, bearing 235.7. Fluctuations in energy
							readings from it, Captain. All transporters off. A strange set-up, but I'd say the graviton
							generator is depolarized. The dark colourings of the scrapes are the leavings of natural
							rubber, a type of non-conductive sole used by researchers experimenting with electricity.
							The molecules must have been partly de-phased by the anyon beam.
						</p>

					</div>
				</div>
			</div>
			<div class="col-md-5">
				<div class="row">
					<div class="col-md-12">
						<!--				<iframe width="600" height="450" frameborder="0" style="border:0"-->
						<!--						src="https://www.google.com/maps/embed/v1/directions?origin=Albuquerque,+NM,+United+States&destination=Jack+in+the+Box,+Montgomery+Boulevard+Northeast,+Albuquerque,+NM,+United+States&key=..."-->
						<!--						allowfullscreen></iframe>-->
						<iframe
							src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12947831.742778081!2d-95.665!3d37.599999999999994!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x54eab584e432360b%3A0x1c3bb99243deb742!2sUnited+States!5e0!3m2!1sen!2sus!4v1439833836049"
							width=100%" height="300" frameborder="0" style="border:0" allowfullscreen></iframe>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">

						<?php require_once(dirname(__DIR__) . "/controllers/restaurant-comment-form.php") ?>

						<?php

						$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/trufork.ini");

						$comments = Comment::getCommentByRestaurantId($pdo, $restaurantId);

						foreach($comments as $comment) {
							$commentContent = $comment->getcontent();
							$user = User::getUserByUserId($pdo, $comment->getUserId());
							$userName = $user->getName();
							$commentDate = $comment->getDateTime();
							require(dirname(__DIR__) . "/lib/comment.php");
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>