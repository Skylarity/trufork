<?php if(empty($_SESSION["user"])) { ?>

	<ul class="nav nav-pills pull-right">
		<!--Button Trigger User Login--> 
		<li>
			<button type="button" class="modal-button btn-lg" data-toggle="modal" data-target="#signup"> Sign Up</button>
		</li>
		<li>
			<button type="button" class="modal-button btn-lg" data-toggle="modal" data-target="#login">Log In</button>
		</li>
	</ul>

<?php } ?>

<?php
//is session is empty
if(!empty($_SESSION["user"])) { ?>


	<ul class="nav nav-pills pull-right">
		<!-- Button for logged-in users-->
		<li>
			<?php echo "<p> <i class=\"fa fa-user\"></i>". $_SESSION['user']->getName() . "</p>"; ?>
		</li>

		<li>
			<a href="<?php echo $PREFIX;?>php/controllers/log-out-controller.php" class="btn btn-lg">Log Out</a>
		</li>
	</ul>

	<?php } ?>