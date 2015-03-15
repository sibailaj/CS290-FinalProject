<?php
	ini_set('display_errors', 'On');

	$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "sibailaj-db", "j1nl10en0wr49WVv", "sibailaj-db");

	if ($mysqli->connect_errno) {
	  echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	} else {

	}

	//Display All Results
	if (isset($_POST['filter']) && isset($_POST['sort']) && isset($_POST['orderby'])) {
		$selection = "SELECT P.id, P.fname, P.lname, P.position, P.height, P.weight, 
			P.birthdate, C.location, C.mascot, P.forty, P.threecone, P.shuttle, P.vertical, P.broad, P.bench, 
			P.rotoworld FROM fp_players P INNER JOIN fp_colleges C ON P.college = C.id " . $_POST['filter']
			. " ORDER BY " . $_POST['sort'] . " " . $_POST['orderby'];
		$playerTableStatement = $mysqli->prepare($selection);
		$playerTableStatement->execute();
		$playerTableStatement->bind_result($id, $fname, $lname, $position, $height, $weight, $birthdate, $location,$mascot, $forty, $threecone, $shuttle, $vertical, $broad, $bench, $rotoworld);
		echo "<table><tr><td>First Name<td>Last Name<td>Position<td>Height<td>Weight<td>Birthdate<td>College<td>
		40 Yard Dash<td>3-Cone Time<td>Shuttle<td>Vertical<td>Broad Jump<td>Bench Press<td>Rotoworld Link<td>Detail";
		while($playerTableStatement->fetch()) {
			echo "<tr><td>" . $fname . "<td>" . $lname . "<td>" . $position . "<td>" . $height . "<td>" .
			$weight . "<td>" . $birthdate . "<td>" . $location . " " . $mascot . "<td>" . $forty . "<td>" .
			$threecone . "<td>" . $shuttle . "<td>" . $vertical . "<td>" . $broad . "<td>" . $bench . "<td>" .
			"<a href='" . $rotoworld . "'>Link</a>" . "<td>" . "<button name='detailbutton' onclick='playerDetail(this.value)' value='" . $id . "'>Detail</button>";
		}
		echo "</table>";
		$playerTableStatement->close();			
	}

	//Player Details
	//Awards
	if (isset($_POST['id'])) {
		$playerAwardsStatement = $mysqli->prepare("SELECT P.fname, P.lname, PA.year, A.award FROM fp_awards A INNER JOIN fp_players_awards PA
			ON A.id = PA.aid INNER JOIN fp_players P ON P.id = PA.pid WHERE P.id = ?");
		$playerAwardsStatement->bind_param("i", $_POST['id']);
		$playerAwardsStatement->execute();
		$playerAwardsResult = $playerAwardsStatement->bind_result($firstName, $lastName, $year, $award);
		$playerAwardsStatement->fetch();
		echo "<h1>" . $firstName . " " . $lastName . " Player Detail:</h1><br><br>";
		echo "<h2>Awards: </h2><br>";
		if ($playerAwardsResult) {
			echo $year . " " . $award . "<br>";
			while($playerAwardsStatement->fetch()) {
				echo $year . " " . $award . "<br>";
			}
		} else {
			echo "N/A<br>";
		}
		$playerAwardsStatement->close();

		//Redflags
		$playerRedflagStatement = $mysqli->prepare("SELECT R.redflag FROM fp_redflags R INNER JOIN
			fp_players_redflags PR ON R.id = PR.rid INNER JOIN fp_players P ON P.id = PR.pid WHERE P.id = ?");
		$playerRedflagStatement->bind_param("i", $_POST['id']);
		$playerRedflagStatement->execute();
		$playerRedflagStatement->bind_result($redflag);
		echo "<h2>Red Flags:</h2><br>";
		$playerRedflagResult = $playerRedflagStatement->fetch();
		if ($playerRedflagResult !== NULL) {
			echo $redflag . "<br>";
			while($playerRedflagStatement->fetch()) {
				echo $redflag . "<br>";
			}		
		} else {
			echo "N/A<br>";
		}
		$playerRedflagStatement->close();

		//Potential Landing Spots
		$playerLandingspotStatement = $mysqli->prepare("SELECT L.landingspot FROM fp_landingspots L INNER JOIN
			fp_players_landingspots PL ON L.id = PL.lid INNER JOIN fp_players P ON P.id = PL.pid WHERE P.id = ?");
		$playerLandingspotStatement->bind_param("i", $_POST['id']);
		$playerLandingspotStatement->execute();
		$playerLandingspotStatement->bind_result($landingspot);
		echo "<h2>Potential Landing Spots:</h2><br>";
		$playerLandingspotResult = $playerLandingspotStatement->fetch();
		if ($playerLandingspotResult !== NULL) {
			echo $landingspot . "<br>";
			while($playerLandingspotStatement->fetch()) {
				echo $landingspot . "<br>";
			}
		} else {
			echo "N/A<br>";
		}
		$playerLandingspotStatement->close();
	}

?>