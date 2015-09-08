<div class="container">
	<div class="row">
		<div class="col-md-12">
			<h1 class="search-main-h1">Find a delicious, clean place to eat.</h1>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<form id="main-search" method="get" action="<?php echo $PREFIX ?>php/controllers/google-api-controller.php">
				<div class="main-search-align">
					<div class="input-group">
						<input id="userQuery" name="userQuery" type="text" class="form-control main-search input-lg"
							   placeholder="Search in Albuquerque">
						<span class="input-group-btn">
							<button id="restaurant-search-submit" type="submit" class="btn btn-lg main-search-button"><i
									class="fa fa-search"></i></button>
						</span>
					</div>
				</div>
			</form>
			<div id="outputArea"></div>
			<!--call this something else?????-->
			<script type="text/javascript"
					src="<?php echo $PREFIX; ?>php/controllers/global-text-search-controller.js"></script>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<form id="search-by-float-rating-form" method="get"
				  action="<?php echo $PREFIX ?>php/controllers/search-by-float-rating-controller.php">
				<div class="trufork-search-align">
					<div class="box-rating col-md-10">
						<fieldset class="rating">
							<input class="star star-5" id="star-5" name="rating[]" type="radio" value="5"/>
							<label class="star star-5" for="star-5"></label>
							<input class="star star-4" id="star-4" name="rating[]" type="radio" value="4"/>
							<label class="star star-4" for="star-4"></label>
							<input class="star star-3" id="star-3" name="rating[]" type="radio" value="3"/>
							<label class="star star-3" for="star-3"></label>
							<input class="star star-2" id="star-2" name="rating[]" type="radio" value="2"/>
							<label class="star star-2" for="star-2"></label>
							<input class="star star-1" id="star-1" name="rating[]" type="radio" value="1"/>
							<label class="star star-1" for="star-1"></label>
							<!--							<input class="star star-0" id="star-0" name="rating[]" type="radio" value="0"/>-->
							<!--							<label class="star star-0" for="star-0"></label>-->
						</fieldset>
					</div>
					<div class="button-group col-md-2">
						<button type="submit" class="trufork-search-button">Submit</button>
						<!--						<button type="reset" class="btn btn-default">Reset</button>-->
					</div>
				</div>
			</form>
			<div id="outputArea"></div>
			<script type="text/javascript"
					src="<?php echo $PREFIX; ?>php/controllers/search-by-float-rating-controller.js"></script>
		</div>
	</div>
</div>
