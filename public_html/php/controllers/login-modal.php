<!-- login modal -->
<div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="login modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h3 class="modal-title" id="login">Log In</h3>
			</div> 
			<div class="modal-body">
				<form id="login-controller" class="form" method="post" action="<?php echo $PREFIX?>php/controllers/login-controller.php">
					<div class="form-group">
						<label for="loginEmail">email</label>
						<input type="text" class="form-control" id="loginEmail" name="loginEmail" placeholder="Email">
					</div>
					<div class="form-group">
						<label for="loginPassword">Password</label>
						<input type="password" class="form-control" id="loginPassword" name="loginPassword" placeholder="password">
					</div>
					<div class="form-group">
						<button type="submit" class="btn modal-button">Login</button>
					</div>
				</form>
				<div id="outputArea-login"></div>
			</div>
		</div>
	</div>
</div>