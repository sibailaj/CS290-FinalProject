<?php
	ini_set('display_errors', 'On');

	$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "sibailaj-db", "j1nl10en0wr49WVv", "sibailaj-db");

	if ($mysqli->connect_errno) {
	  echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	} else {

	}

?>

<html>
  <head>
  	<meta charset = "UTF-8">
  	<title>Home</title>
  </head>
  <body>
    yo
  </body>
</html>