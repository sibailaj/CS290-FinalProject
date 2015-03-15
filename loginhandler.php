<?php
	session_start();

	ini_set('display_errors', 'On');

	$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "sibailaj-db", "j1nl10en0wr49WVv", "sibailaj-db");

	if ($mysqli->connect_errno) {
	  echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	} else {

	}

	if (empty($_POST['username']) || empty($_POST['password'])) {
		if (empty($_POST['username'])) {
			echo "<font color='red'>Error: Username cannot be blank.</font><br>";
		}
		if (empty($_POST['password'])) {
			echo "<font color='red'>Error: Password cannot be blank.</font><br>";
		}
	} else {
	    //Prepared statement to Insert Username and Password into fp_accounts table
	    $statement = $mysqli->prepare("SELECT DISTINCT username, password FROM fp_accounts WHERE username = ? AND password = ?");
	    $statement->bind_param("ss", $_POST['username'], $_POST['password']);
	    $statement->execute();
	    $statement->bind_result($usernameResult, $passwordResult);
	    $fetchResult = $statement->fetch();
	    if ($fetchResult === NULL) {
	    	echo "<font color='red'>Error: Incorrect Username or Password.</font><br>";
	    } else {
	    	$_SESSION['username'] = $usernameResult;
	    	$_SESSION['login'] = true;
	    }

	    $statement->close(); 		
	}
?>
