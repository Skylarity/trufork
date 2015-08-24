<form id="search-by-float-rating-form" action="../controllers/search-by-float-rating-controller.php" method="post">

<div class="search-by-float-rating-form-group">
	<h2>Search by TruFork Rating</h2>

<form action="#" method="POST">
	<div class="box-rating clearfix">

		<fieldset class="box-rating-widget inline-block">
			<legend class="offscreen">TruFork Rating</legend>
			<ul class="boxes1-5">
				<li>
					<input id="rating-5" name="rating" type="checkbox" value="5">
					<label for="rating-5">5: Highest reviews and impeccable inspection records. Buen provecho!</label>
				</li>
				<li>
					<input id="rating-4-4.9" name="rating" type="checkbox" value="4-4.9">
					<label for="rating-4-4.9">4 - 4.9: Good reviews and inspection records. Proceed.</label>
				</li>
				<li>
					<input id="rating-3-3.9" name="rating" type="checkbox" value="3-3.9">
					<label for="rating-3-3.9">3 - 3.9: Caution. Reviews and inspection records may indicate trouble.</label>
				</li>
				<li>
					<input id="rating-2-2.9" name="rating" type="checkbox" value="2-2.9">
					<label for="rating-2-2.9">2 - 2.9: I too like to live dangerously.</label>
				</li>
				<li>
					<input id="rating-1-1.9" name="rating" type="checkbox" value="1-1.9">
					<label for="rating-1-1.9">1 - 1.9: Unless this place is new and has no reviews, avoid like the plague.</label>
				</li>
			</ul>
			<p class="description">Choose one or more rating levels, then click Submit.</p>

			<div class="button-group">
				<button type="submit" class="btn btn-primary">Submit</button>
				<button type="reset" class="btn btn-default">Reset</button>
			</div>
	</div>

</form>
			<div id="outputArea"></div>

			<script type="text/javascript" src= "<?php echo $PREFIX; ?>php/controllers/search-by-float-rating-controller.js"></script>


