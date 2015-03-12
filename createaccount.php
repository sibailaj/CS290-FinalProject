<?php
ini_set('display_errors', 'On');

$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "sibailaj-db", "j1nl10en0wr49WVv", "sibailaj-db");

if ($mysqli->connect_errno) {
  echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
} else {

}

if (isset($_POST['accountSubmit'])) {
  if (empty($_POST['userName']) || (empty($_POST['passWord']))) {
    if (empty($_POST['userName'])) {
      echo "<font color='red'>Error: Username cannot be blank.</font><br>";
    }
    if (empty($_POST['passWord'])) {
      echo "<font color='red'>Error: Password cannot be blank.</font><br>";      
    }
  } else {
    //Prepared statement to Insert Username and Password into fp_accounts table
    $statement = $mysqli->prepare("INSERT INTO fp_accounts (username, password) VALUES (?, ?)");
    $statement->bind_param("ss", $_POST['userName'], $_POST['passWord']);
    $result = $statement->execute();
    if ($result == false) {
      echo "<font color='red'>Error: Username already taken.</font><br>";
    }
    $statement->close();    
  }
}

?>

<html>
  <head>
  	<meta charset = "UTF-8">
  	<title>Create Account</title>
  </head>
  <body>
  <form action="" method="post">
  	Create Your Account
    <br><br>
    <font color="red">*</font> New Username:
    <br>
  	<input type="text" name="userName">
  	<br><br>
    <font color="red">*</font> New Password:
    <br>
  	<input type="password" name="passWord">
    <br>
    <br>
    <font color="red">*</font> Required Field
    <br>
    <button type="submit" name="accountSubmit">Submit</button>
  </form>
  </body>
</html>