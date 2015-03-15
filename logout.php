<?php
	session_start();
	$_SESSION = array();
	session_destroy();
	echo "You have successfully logged out!<br>";
	echo "Click <a href='http://web.engr.oregonstate.edu/~sibailaj/finalproject/src/login.php'>here</a> to log in.";

?>