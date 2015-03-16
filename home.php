<?php
  session_start();

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
  	<title>Home</title>
    <link rel="stylesheet" href="style.css">
  	<script>
  	function display() {
  		var filterBy = document.getElementById("filter").value;
  		var sortBy = document.getElementById("sort").value;
  		var orderBy;
  		if (document.getElementsByName("orderby")[0].checked) {
  			orderBy = document.getElementsByName("orderby")[0].value;
  		} else {
  			orderBy = document.getElementsByName("orderby")[1].value;
  		}

  		var httpRequest = new XMLHttpRequest();
      	var url = "http://web.engr.oregonstate.edu/~sibailaj/finalproject/src/displayresults.php";

      	if (httpRequest) {
	        httpRequest.onreadystatechange = handle;
	        httpRequest.open('POST', url, true);
	        httpRequest.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	        httpRequest.send("filter=" + filterBy + "&" + "sort=" + sortBy + "&" + "orderby=" + orderBy);
    	}

        function handle() {
	        if (httpRequest.readyState === 4) {
	          if (httpRequest.status === 200) {
	            document.getElementById('response').innerHTML = httpRequest.responseText;
	          } else {
	            //alert ("error");
	          }
        	} 
      	}
  	}

  	function playerDetail(value) {
  		var playerID = value;

  		var httpRequest = new XMLHttpRequest();
      	var url = "http://web.engr.oregonstate.edu/~sibailaj/finalproject/src/displayresults.php";

      	if (httpRequest) {
	        httpRequest.onreadystatechange = handle;
	        httpRequest.open('POST', url, true);
	        httpRequest.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	        httpRequest.send("id=" + playerID);
    	}

    	function handle() {
	        if (httpRequest.readyState === 4) {
	          if (httpRequest.status === 200) {
	            document.getElementById('detailresponse').innerHTML = httpRequest.responseText;
	          } else {
	            //alert ("error");
	          }
        	} 
      	}
  	}

    function addFavorite(value) {
      var playerID = value;
      //var userName = $_SESSION['username'];

      var httpRequest = new XMLHttpRequest();
      var url = "http://web.engr.oregonstate.edu/~sibailaj/finalproject/src/displayresults.php";

      if (httpRequest) {
        httpRequest.onreadystatechange = handle;
        httpRequest.open('POST', url, true);
        httpRequest.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        httpRequest.send("id=" + playerID + "&add=1");
    }

    function handle() {
        if (httpRequest.readyState === 4) {
          if (httpRequest.status === 200) {
            document.getElementById('addplayerresponse').innerHTML = httpRequest.responseText;
          } else {
            //alert ("error");
          }
        } 
      }
    }
  	</script>
  </head>
  <body>
  <h2>Database Home</h2>
  <div class="pagestyle">
    Welcome to the Database Home page! Perform a search using the below criteria. Click the Detail button
    to get additional details on the player. Click the Favorite button to add the player to your account's
    Favorites List.
    <div class="usermenu">
<?php
  echo "Welcome, " . $_SESSION['username'] . "!<br>";
  echo "<a href='http://web.engr.oregonstate.edu/~sibailaj/finalproject/src/favorites.php'>Favorites</a>&nbsp;&nbsp;";
  echo "<a href='http://web.engr.oregonstate.edu/~sibailaj/finalproject/src/logout.php'>Logout</a>";

?>
    </div>
  <br><br>
  <a href='http://web.engr.oregonstate.edu/~sibailaj/finalproject/src/addplayer.php'>Add Player</a>
  <br><br>
  <div id="addplayerresponse"></div>
  <form>
  	Filter By:
  	<select id="filter">
  	  <option value="">All</option>
  	  <option value="WHERE P.position = 'QB'">QB</option>
  	  <option value="WHERE P.position = 'RB'">RB</option>
  	  <option value="WHERE P.position = 'WR'">WR</option>
  	  <option value="WHERE P.position = 'TE'">TE</option>
  	</select>
  	Sort By:
  	<select id="sort">
  	  <option value="P.lname">Last Name</option>
  	  <option value="P.fname">First Name</option>
  	  <option value="P.height">Height</option>
  	  <option value="P.weight">Weight</option>
  	  <option value="P.birthdate">Birthdate</option>
  	  <option value="C.location">College</option>
  	  <option value="P.forty">40 Yard Dash</option>
  	  <option value="P.threecone">3-Cone Time</option>
  	  <option value="P.shuttle">Shuttle</option>
  	  <option value="P.vertical">Vertical</option>
  	  <option value="P.broad">Broad Jump</option>
  	  <option value="P.bench">Bench Press</option>
  	</select>
  	<input type="radio" name="orderby" value="" checked="checked">Ascending</input>
  	<input type="radio" name="orderby" value="DESC">Decending</input>
  	<input type="button" id="sortbutton" value="Submit" onclick="display()" class="submit">
  </form>
  <div id="response"></div>
  <br><br>
  <div id="detailresponse"></div>
  </div>
  </body>
</html>