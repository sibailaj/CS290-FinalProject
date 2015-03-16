<?php
	session_start();
	$_SESSION = array();
	session_destroy();
?>
<html>
  <head>
  	<meta charset = "UTF-8">
  	<link rel="stylesheet" href="style.css">
  </head>
  <body>
  <h2>Logged Out</h2>
  <div class="pagestyle">

<?php
	echo "You have successfully logged out!<br>";
	echo "Click <a href='http://web.engr.oregonstate.edu/~sibailaj/finalproject/src/login.php'>here</a> to log in.";

?>
  </body>
</html>