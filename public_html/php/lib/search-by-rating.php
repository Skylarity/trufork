<!DOCTYPE HTML>
	<html>
		<head>
			<meta http-equiv="Content-Type" content="text/html">
			<title>Search by TruFork Rating</title>
		</head>
		<p>
			<body>
				<h3>Search by TruFork Rating</h3>
				<p>Here, you can search for restaurants by a TruFork Rating.</p>
		<form method="post" action="search.php?go" id="searchform">
			<input type="float" name="TruFork Rating">
			<input type="submit" name="submit" value="Search">
		</form>
		<?php
		if(isset($_POST['submit'])){

		}
		else{
			echo "<p>Please enter a search query.</p>";
		}
		?>
	</body>
	</html>