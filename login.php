<?php
  
  session_start();
  ini_set('display_errors', 'On');

  if (isset($_SESSION['login'])) {
    header("Location: http://web.engr.oregonstate.edu/~sibailaj/finalproject/src/home.php", true);
  }


?>

<html>
  <head>
    <script>
    function login() {
      var username = document.getElementById("userName").value;
      var password = document.getElementById("passWord").value;

      var httpRequest = new XMLHttpRequest();
      var url = "http://web.engr.oregonstate.edu/~sibailaj/finalproject/src/loginhandler.php";
      var success;

      if (httpRequest) {
        httpRequest.onreadystatechange = handle;
        httpRequest.open('POST', url, true);
        httpRequest.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        httpRequest.send("username=" + username + "&" + "password=" + password);
      }

      function handle() {
        if (httpRequest.readyState === 4) {
          if (httpRequest.status === 200) {
            if (httpRequest.responseText) {
              document.getElementById('response').innerHTML = httpRequest.responseText;
            } else {
              location.reload();
            }
                        
          } else {
            //alert ("error");
          }
        } 
      }
    }
    </script>
  	<title>Login</title>
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
    <h1>Dynasty Rookie Database</h1>
    <div class="loginbox">
    <form>
      <div class="loginheader">Account Login</div>
      <div id="response"></div>
      <font color="red">*</font> Username:
      <br>
    	<input type="text" id="userName">
    	<br><br>
      <font color="red">*</font> Password:
      <br>
    	<input type="password" id="passWord">
      <br>
      <br>
      <font color="red">*</font> Required Field
      <br>
      <input type="button" value="Submit" onclick="login()" class="submit">
    </form>
    <a href="createaccount.php">Create New Account</a>
    </div>
    <p>This website is a database representing the 2015 NFL Skill Position Rookies that are entering this year’s NFL Draft. The skill positions include draft eligible quarterbacks, running backs, wide receivers and tight ends. These positions are critical to fantasy football players in a keeper/dynasty setting as promising rookies can carry more long-term upside than older veteran players. Note: Not all eligible rookies have been entered into the database.</p>
    <p>Please Login or Create a New Account to access the site!</p>
  </body>
</html>