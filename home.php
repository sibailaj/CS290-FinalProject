<html>
  <head>
  	<meta charset = "UTF-8">
  	<title>Home</title>
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
  		//var sortbutton = document.getElementById("sortbutton").value;

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

  		/*
  		for (var i in document.getElementsByName("detailbutton")) {
  			if (document.getElementsByName("detailbutton")[i].checked) {
  				playerID = document.getElementsByName("detailbutton")[i].value;
  			}
  		}
  		*/

  		//var playerID = document.getElementsByName("detailbutton").value;

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
  	</script>
  </head>
  <body>
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
  	<input type="button" id="sortbutton" value="Submit" onclick="display()">
  </form>
  <div id="response"></div>
  <br><br>
  <div id="detailresponse"></div>
  </body>
</html>