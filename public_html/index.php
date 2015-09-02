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
			<?php require_once("php/lib/search-main.php"); ?>
			<?php require_once("php/controllers/sign-up-login-modal.php") ?>
			<?php require_once("php/controllers/login-modal.php") ?>
		</div>
	</div>
	<footer>
		<?php require_once("php/lib/footer.php"); ?>
	</footer>

</body>
</html>