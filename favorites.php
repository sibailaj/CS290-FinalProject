<?php
	session_start();

	$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "sibailaj-db", "j1nl10en0wr49WVv", "sibailaj-db");

	if ($mysqli->connect_errno) {
	  echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	} else {

	}

	if(!isset($_SESSION['login'])) {
	    $filePath = explode('/', $_SERVER['PHP_SELF'], -1);
	    $filePath = implode('/', $filePath);
	    $redirect = "http://" . $_SERVER['HTTP_HOST'] . $filePath;
	    header("Location: {$redirect}/login.php", true);
	    exit();
  	}

  	echo "<h1>Favorites:</h1>";
  	//Display Favorites
  	$favoriteTable = $mysqli->prepare("SELECT P.fname, P.lname, P.position FROM fp_players P
  		INNER JOIN fp_favorites F ON P.id = F.pid
  		INNER JOIN fp_accounts A ON F.aid = A.id");
  	$favoriteTable->execute();
  	$favoriteTable->bind_result($fname, $lname, $position);
  	echo "<table><tr><td>First Name<td>Last Name<td>Position";
  	while($favoriteTable->fetch()) {
  		echo "<tr><td>" . $fname . "<td>" . $lname . "<td>" . $position;
  	}
  	$favoriteTable->close();

  	echo "</table><br><br><form action='http://web.engr.oregonstate.edu/~sibailaj/finalproject/src/home.php'>
  		<input type='submit' value='OK'></form>";

?>