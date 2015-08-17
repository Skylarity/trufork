<?php
$CURRENT_DIR = __DIR__;
$PAGE_TITLE = "TruFork";
require_once("../php/lib/head-utils.php");
?>
<div class="container" xmlns="http://www.w3.org/1999/html">
	<div class="row">
		<div class="col-md-3">
			<a class="home-link" href=".">
				<span class=" title">
				<img class="logo" src="<?php echo $PREFIX; ?>images/fork.svg" alt="TruFork Logo"/>
				TruFork
				</span>
			</a>
		</div>
		<div class="col-md-5">
			<form id="header-search" method="get" action="">
				<div class="inline-block search-bar-left">
					<input class="header-search" type="search" placeholder="Search in Albuquerque"/>
				</div>
				<div class="inline-block search-bar-right">
					<input class="header-search-button" type="submit" value="Search"/>
				</div>
			</form>
		</div>
		<div class="col-md-4">
			<ul class="nav nav-pills pull-right">
				<li role="presentation"><a href="#">Login/Register</a></li>
				<li role="presentation"><a href="epic/epic.php">Epic</a></li>
				<li role="presentation"><a href="epic/epic-addendum.php">Addendum</a></li>
			</ul>
		</div>
	</div>
</div>