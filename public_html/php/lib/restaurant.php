<?php
require_once(dirname(__DIR__) . "/classes/comment.php");
require_once(dirname(__DIR__) . "/classes/autoload.php");
require_once(dirname(__DIR__) . "/classes/user.php");
require_once(dirname(__DIR__) . "/controllers/sign-up-login-modal.php");
require_once(dirname(__DIR__) . "/controllers/login-modal.php");
require_once("/etc/apache2/data-design/encrypted-config.php");

$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/trufork.ini");
$restaurantId = filter_input(INPUT_GET, "restaurantId", FILTER_VALIDATE_INT);
$restaurant = Restaurant::getRestaurantById($pdo, $restaurantId)
?>
<div class="restaurant">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-6">
						<div class="col-md-12">
							<h1 class="restaurant-name">
								<?php echo $restaurant->getName(); ?>
							</h1>

							<p>
								<?php echo $restaurant->getAddress(); ?>
							</p>
						</div>
						<div class="col-md-12">
							<div class="restaurant-rating">
								<?php
								$rating = $restaurant->getForkRating();
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
					<div class="col-md-6">
						<div class="col-md-12">

							<?php require_once(dirname(__DIR__) . "/controllers/restaurant-comment-form.php") ?>

							<?php

							$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/trufork.ini");

							$comments = Comment::getCommentByRestaurantId($pdo, $restaurantId);
							$comments = array_reverse($comments->toArray());
							?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<h2 class="restaurant-inspection-h2">
							Inspection Information
						</h2>

						<?php
						$violations = Violation::getViolationByRestaurantId($pdo, $restaurantId);
						foreach($violations as $violation) {
							$info = $violation->getViolationDesc();
							if(empty($info) === false) {
								require($PREFIX . "php/lib/inspection-info.php");
							}
						}

						if(count($violations) <= 0) {
							?>
							<p>
								All clean!
							</p>
							<?php
						}
						?>
					</div>
					<div class="col-md-6">
						<?php
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