<?php
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}
unset($_SESSION["user"]);
//header("Refresh:0");
//echo "foo!";
header("Location:index.php");
