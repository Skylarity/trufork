<!DOCTYPE HTML>

<?php
require_once(dirname(__DIR__) . "/classes/restaurant.php");
require_once(dirname(__DIR__) . "/lib/xsrf.php");
require_once("/etc/apache2/data-design/encrypted-config.php");
?>

<div class="um" id="search-by-trufork-rating">
	<h2>Search by TruFork Rating</h2>
	<!-- <div class="content">
		<div class="main-container rating-widget rating-widget">

		</div>
	</div> -->
</div>
<form action="#" method="POST">
	<div class="review-stars clearfix">
		<fieldset class="star-rating-widget inline-block">
			<legend class="offscreen">Rating</legend>
			<ul class="stars-0">
				<li>
					<input id="rating-5" name="rating" type="radio" value="5">
					<label for="rating-5">5 (Highest reviews and impeccable inspection records.)</label>
				</li>
				<li>
					<input id="rating-4-4.9" name="rating" type="radio" value="1">
					<label for="rating-4-4.9">4 (Good reviews and inspection records.)</label>
				</li>
				<li>
					<input id="rating-3-3.9" name="rating" type="radio" value="2">
					<label for="rating-3-3.9">3 (Caution. Reviews and inspection records may indicate trouble.)</label>
				</li>
				<li>
					<input id="rating-2-2.9" name="rating" type="radio" value="3">
					<label for="rating-2-2.9">2 (I too like to live dangerously.)</label>
				</li>
				<li>
					<input id="rating-1-1.9" name="rating" type="radio" value="4">
					<label for="rating-1-1.9">1 (Unless this place is new and has no reviews, avoid like the plague.)</label>
				</li>
			</ul>
			<p class="description">Roll over stars, choose a rating, then click Search.</p>
