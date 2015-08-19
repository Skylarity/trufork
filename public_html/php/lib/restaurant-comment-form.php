<form id="restaurant-comment-form" action="<?php echo $PREFIX; ?>php/controllers/restaurant-comment-controller.php" method="post">
	<?php
	$restaurantId = filter_input(INPUT_GET, "restaurantId", FILTER_VALIDATE_INT);
	if($restaurantId !== false && $restaurantId > 0) {
	?>
	<input type="hidden" name="restaurantId" value="<?php echo $restaurantId; ?>"/>

	<div class="restaurant-comment-form-group">
			<label for="txtComment">Restaurant Comment Field</label>
			<textarea name="txtComment" id="txtComment" class="input-control" rows="6"
						cols="50" maxlength="1064">Whatchoo tryna say about this restaurant?</textarea>
	</div>

	<div class="button-group">
		<button type="submit" class="btn btn-primary">Submit</button>
		<button type="reset" class="btn btn-default">Reset</button>
	</div>

</form>


<div id="outputArea"></div>

<script type="text/javascript" src= "<?php echo $PREFIX; ?>php/controllers/restaurant-comment-controller.js"></script>

<?php
}
?>