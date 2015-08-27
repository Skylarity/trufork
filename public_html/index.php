<?php
$CURRENT_DIR = __DIR__;
$PAGE_TITLE = "TruFork";
require_once("php/lib/head-utils.php");
?>
<body class="sfooter">
	<div class="sfooter-content">
		<header>
			<?php require_once("php/lib/header.php"); ?>
		</header>
		<div class="main-text">
			<?php require_once("php/lib/sign-up-login-modal.php"); ?>
			<?php require_once("php/lib/search-main.php"); ?>
		</div>
	</div>
	<footer>
		<?php require_once("php/lib/footer.php"); ?>
	</footer>
	<script type="text/javascript" src="php/controllers/sign-up-controller.js"></script>
</body>
</html>