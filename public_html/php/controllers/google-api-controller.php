<!DOCTYPE html>
<html>
	<body>

		<?php
		$url = "https: //maps.googleapis.com/map/api/textsearch/json?.key=$config["AIzaSyDrzVsv2pExMzhxY-EkXNyaEmOtZkA8Qac"]&location = 35.08574,-106.64953. & radius=20000.&types=cafe|food|restaurant&query=$userquery";

		// Remove all illegal characters from a url
		$url = filter_var($url, FILTER_SANITIZE_URL);

		// Validate url
		if (!filter_var($url, FILTER_VALIDATE_URL) === false) {
			echo("$url is a valid URL");
		} else {
			echo("$url is not a valid URL");
		}
		?>

	</body>
</html>


