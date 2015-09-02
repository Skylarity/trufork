<?php
session_start();
unset($_SESSION["user"]);
unset($_SESSION["userName"]);
//header("Refresh:0");
echo "foo!";
header("Location:/~kchavez68/trufork/public_html");


