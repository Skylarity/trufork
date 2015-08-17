<?php
$CURRENT_DIR = __DIR__;
$PAGE_TITLE = "TruFork - Restaurant";
require_once("../php/lib/head-utils.php");
?>
<body class="sfooter">
	<div class="sfooter-content">
		<header>
			<?php require_once("../php/lib/header.php"); ?>
		</header>
		<?php require_once("../php/lib/restaurant.php"); ?>
	</div>
	<footer>
		<?php require_once("../php/lib/footer.php"); ?>
	</footer>
</body>
</html>