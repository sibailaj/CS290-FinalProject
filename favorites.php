<?php
	session_start();
	ini_set('display_errors', 'On');

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
?>

<html>
  <head>
  	<meta charset = "UTF-8">
  	<link rel="stylesheet" href="style.css">
  </head>
  <body>
  <div class="pagestyle">

<?php
  	//Delete Button
	if (isset($_POST['deletebutton'])) {
		//Get User ID value
		$userIDStatement = $mysqli->prepare("SELECT id FROM fp_accounts WHERE username = ?");
		$userIDStatement->bind_param("s", $_SESSION['username']);
		$userIDStatement->execute();
		$userIDStatement->bind_result($userID);
		$userIDStatement->fetch();
		$userIDStatement->close();

		//Delete Row from Favorites
		$statement = $mysqli->prepare("DELETE FROM fp_favorites WHERE aid = ? AND pid = ?;");
		$statement->bind_param("ii", $userID, $_POST['deletebutton']);
		$statement->execute();
		$statement->close();
	}

  	echo "<h2>Favorites</h2>";
  	//Display Favorites
  	$favoriteTable = $mysqli->prepare("SELECT P.id, P.fname, P.lname, P.position FROM fp_players P
  		INNER JOIN fp_favorites F ON P.id = F.pid
  		INNER JOIN fp_accounts A ON F.aid = A.id WHERE A.username = ?");
  	$favoriteTable->bind_param("s", $_SESSION['username']);
  	$favoriteTable->execute();
  	$favoriteTable->bind_result($playerID, $fname, $lname, $position);
  	echo "<form action='' method='POST'>";
  	echo "<table class='center'><tr><td>First Name<td>Last Name<td>Position<td>Delete";
  	while($favoriteTable->fetch()) {
  		echo "<tr><td>" . $fname . "<td>" . $lname . "<td>" . $position . "<td>" . "<button type='submit'
  		class='submit' name='deletebutton' value='" . $playerID . "''>Delete</button>";
  	}
  	echo "</table></form>";
  	$favoriteTable->close();

  	echo "</table><br><br><form action='http://web.engr.oregonstate.edu/~sibailaj/finalproject/src/home.php'>
  		<input type='submit' class='submit' value='OK'></form></div>";

?>
  </body>
</html>