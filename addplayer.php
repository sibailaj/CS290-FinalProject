<?php
	session_start();
  ini_set('display_errors', 'On');

  if(!isset($_SESSION['login'])) {
    $filePath = explode('/', $_SERVER['PHP_SELF'], -1);
    $filePath = implode('/', $filePath);
    $redirect = "http://" . $_SERVER['HTTP_HOST'] . $filePath;
    header("Location: {$redirect}/login.php", true);
    exit();
  }

	$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "sibailaj-db", "j1nl10en0wr49WVv", "sibailaj-db");

	if ($mysqli->connect_errno) {
	  echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	} else {

	}

  //Error Handling

  if (isset($_POST['firstname']) && isset($_POST['lastname'])) {
    if ((!ctype_alpha($_POST['firstname']) && !empty($_POST['firstname'])) || (!ctype_alpha($_POST['lastname']) && !empty($_POST['lastname'])) || empty($_POST['firstname']) 
      || empty($_POST['lastname']) || (!is_numeric($_POST['forty']) && !empty($_POST['forty'])) || (!is_numeric($_POST['threecone']) && !empty($_POST['threecone'])) ||
      (!is_numeric($_POST['shuttle']) && !empty($_POST['shuttle'])) || (!is_numeric($_POST['vertical']) && !empty($_POST['vertical'])) || (!ctype_digit($_POST['broad']) && !empty($_POST['broad']))
      || (!ctype_digit($_POST['bench']) && !empty($_POST['bench'])) || (!ctype_digit($_POST['weight']) && !empty($_POST['weight'])) || (empty($_POST['collegeLocation']) && !empty($_POST['collegeMascot'])) || (empty($_POST['collegeMascot']) && !empty($_POST['collegeLocation'])) 
      || (empty($_POST['award1']) && !empty($_POST['awardyear1']))
      || (empty($_POST['awardyear1']) && !empty($_POST['award1']))
      || (empty($_POST['award2']) && !empty($_POST['awardyear2']))
      || (empty($_POST['awardyear2']) && !empty($_POST['award2']))
      || (empty($_POST['award3']) && !empty($_POST['awardyear3']))
      || (empty($_POST['awardyear3']) && !empty($_POST['award3']))) {
      if (!ctype_alpha($_POST['firstname']) && !empty($_POST['firstname'])) {
        echo "<font color='red'>Error: Invalid First Name.</font><br>";
      }
      if (!ctype_alpha($_POST['lastname']) && !empty($_POST['lastname'])) {
        echo "<font color='red'>Error: Invalid Last Name.</font><br>";
      }
      if (empty($_POST['firstname'])) {
        echo "<font color='red'>Error: First Name is Required.</font><br>";
      }
      if (empty($_POST['lastname'])) {
        echo "<font color='red'>Error: Last Name is Required.</font><br>";
      }
      if (!is_numeric($_POST['forty']) && !empty($_POST['forty'])) {
        echo "<font color='red'>Error: Invalid 40 Yard Dash.</font><br>";
      }
      if (!is_numeric($_POST['threecone']) && !empty($_POST['threecone'])) {
        echo "<font color='red'>Error: Invalid 3-Cone Time.</font><br>";
      }
      if (!is_numeric($_POST['shuttle']) && !empty($_POST['shuttle'])) {
        echo "<font color='red'>Error: Invalid Shuttle.</font><br>";
      }
      if (!is_numeric($_POST['vertical']) && !empty($_POST['vertical'])) {
        echo "<font color='red'>Error: Invalid Vertical.</font><br>";
      }
      if (!ctype_digit($_POST['broad']) && !empty($_POST['broad'])) {
        echo "<font color='red'>Error: Invalid Broad Jump.</font><br>";
      }
      if (!ctype_digit($_POST['bench']) && !empty($_POST['bench'])) {
        echo "<font color='red'>Error: Invalid Bench Press.</font><br>";
      }
      if (!ctype_digit($_POST['weight']) && !empty($_POST['weight'])) {
        echo "<font color='red'>Error: Invalid Weight.</font><br>";       
      }
      if ((empty($_POST['collegeLocation']) && !empty($_POST['collegeMascot'])) || (empty($_POST['collegeMascot']) && !empty($_POST['collegeLocation']))) {
        echo "<font color='red'>Error: Both College Location and Mascot Must be Populated.</font><br>";
      }
      if ((empty($_POST['award1']) && !empty($_POST['awardyear1']))
      || (empty($_POST['awardyear1']) && !empty($_POST['award1']))
      || (empty($_POST['award2']) && !empty($_POST['awardyear2']))
      || (empty($_POST['awardyear2']) && !empty($_POST['award2']))
      || (empty($_POST['award3']) && !empty($_POST['awardyear3']))
      || (empty($_POST['awardyear3']) && !empty($_POST['award3']))) {
        echo "<font color='red'>Error: Both Award and Year Must be Populated.</font><br>";      
      }    
    } else {

      //Write to College Table
      $collegeStatement = $mysqli->prepare("INSERT INTO fp_colleges (location, mascot) VALUES (?, ?)");
      $collegeStatement->bind_param("ss", $_POST['collegeLocation'], $_POST['collegeMascot']);
      $collegeStatement->execute();
      $collegeStatement->close();

      //Write to Players Table
      //Get College ID First
      $collegeIDStatement = $mysqli->prepare("SELECT id FROM fp_colleges WHERE location = ? AND mascot = ?");
      $collegeIDStatement->bind_param("ss", $_POST['collegeLocation'], $_POST['collegeMascot']);
      $collegeIDStatement->execute();
      $collegeIDStatement->bind_result($collegeID);
      $collegeIDStatement->fetch();
      $collegeIDStatement->close();

      //Write to Table and Include College ID
      //Concatenate Height Feet and Height Inches
      $height;
      if (isset($_POST['height_feet']) && isset($_POST['height_inches'])) {
        $height = $_POST['height_feet'] . "'" . $_POST['height_inches'] . '"';
      }

      //Form Birthdate using Month, Day and Year
      $birthdate;
      if (isset($_POST['birthmonth']) && isset($_POST['birthday']) && isset($_POST['birthyear'])) {
        $birthdate = $_POST['birthyear'] . "-" . $_POST['birthmonth'] . "-" . $_POST['birthday'];
      }

      $playerStatement = $mysqli->prepare("INSERT INTO fp_players (fname, lname, position, height, weight, birthdate, 
        college, forty, threecone, shuttle, vertical, broad, bench, rotoworld) VALUES (?, ?, ?, ?, ?, ?, ?, ?,
        ?, ?, ?, ?, ?, ?)");
      $playerStatement->bind_param("ssssisiddddiis", $_POST['firstname'], $_POST['lastname'], $_POST['position'],
        $height, $_POST['weight'], $birthdate, $collegeID, $_POST['forty'], $_POST['threecone'], $_POST['shuttle'], $_POST['vertical'], $_POST['broad'], $_POST['bench'], $_POST['rotoworld']);
      $playerStatement->execute();
      $playerStatement->close();

      //Write to Awards and Players Awards Tables
      if (!empty($_POST['award1']) && !empty($_POST['awardyear1'])) {
        writeAwards($mysqli, $_POST['award1'], $_POST['awardyear1'], $_POST['firstname'], $_POST['lastname']);
      }
      if (!empty($_POST['award2']) && !empty($_POST['awardyear2'])) {
        writeAwards($mysqli, $_POST['award2'], $_POST['awardyear2'], $_POST['firstname'], $_POST['lastname']);
      }
      if (!empty($_POST['award3']) && !empty($_POST['awardyear3'])) {
        writeAwards($mysqli, $_POST['award3'], $_POST['awardyear3'], $_POST['firstname'], $_POST['lastname']);
      }

      //Red Flag Logic
      if (isset($_POST['injury'])) {
        writeRedFlags($mysqli, $_POST['injury'], $_POST['firstname'], $_POST['lastname']);
      }
      if (isset($_POST['character'])) {
        writeRedFlags($mysqli, $_POST['character'], $_POST['firstname'], $_POST['lastname']);
      }
       if (isset($_POST['other']) && !empty($_POST['other'])) {
        writeRedFlags($mysqli, $_POST['other'], $_POST['firstname'], $_POST['lastname']);
      }



      if (isset($_POST['landingspot'])) {
        foreach ($_POST['landingspot'] as $value) {
          writeLandingspots($mysqli, $value, $_POST['firstname'], $_POST['lastname']);
        }
      }
    }
  }

  function writeRedFlags($mysqli, $redFlagValue, $fName, $lName) {
    //Write to Red Flags Table
    $redFlagStatement = $mysqli->prepare("INSERT INTO fp_redflags (redflag) VALUES (?)");
    $redFlagStatement->bind_param('s', $redFlagValue);
    $redFlagStatement->execute();
    $redFlagStatement->close();

    //Get ID of Red Flag and bind to $redFlagID
    $redFlagIDStatement = $mysqli->prepare("SELECT id FROM fp_redflags WHERE redflag = ?");
    $redFlagIDStatement->bind_param("s", $redFlagValue);
    $redFlagIDStatement->execute();
    $redFlagIDStatement->bind_result($redFlagID);
    $redFlagIDStatement->fetch();
    $redFlagIDStatement->close();

    //Get ID of Player and bind to $playerID
    $playerIDStatement = $mysqli->prepare("SELECT id FROM fp_players WHERE fname = ? AND lname = ?");
    $playerIDStatement->bind_param("ss", $fName, $lName);
    $playerIDStatement->execute();
    $playerIDStatement->bind_result($playerID);
    $playerIDStatement->fetch();
    $playerIDStatement->close();

    //Write $playerID and $redFlagID to fp_players_redflags table
    $playerRedFlagStatement = $mysqli->prepare("INSERT INTO fp_players_redflags (pid, rid) VALUES (?, ?)");
    $playerRedFlagStatement->bind_param("ii", $playerID, $redFlagID);
    $playerRedFlagStatement->execute();
    $playerRedFlagStatement->close();
  }

  function writeLandingspots($mysqli, $value, $fName, $lName) {
    //Write to Landingspot Table
    $landingspotStatement = $mysqli->prepare("INSERT INTO fp_landingspots (landingspot) VALUES (?)");
    $landingspotStatement->bind_param("s", $value);
    $landingspotStatement->execute();
    $landingspotStatement->close();

    //Get ID of Landingspot and bind to $landingspotID
    $landingspotIDStatement = $mysqli->prepare("SELECT id FROM fp_landingspots WHERE landingspot = ?");
    $landingspotIDStatement->bind_param("s", $value);
    $landingspotIDStatement->execute();
    $landingspotIDStatement->bind_result($landingspotID);
    $landingspotIDStatement->fetch();
    $landingspotIDStatement->close();

    //Get ID of Player and bind to $playerID
    $playerIDStatement = $mysqli->prepare("SELECT id FROM fp_players WHERE fname = ? AND lname = ?");
    $playerIDStatement->bind_param("ss", $fName, $lName);
    $playerIDStatement->execute();
    $playerIDStatement->bind_result($playerID);
    $playerIDStatement->fetch();
    $playerIDStatement->close();

    //Write $playerID and $landingspotID to fp_players_landingspots table
    $playerLandingspotStatement = $mysqli->prepare("INSERT INTO fp_players_landingspots (pid, lid) VALUES (?, ?)");
    $playerLandingspotStatement->bind_param("ii", $playerID, $landingspotID);
    $playerLandingspotStatement->execute();
    $playerLandingspotStatement->close();
  }

  function writeAwards ($mysqli, $awardValue, $awardYear, $fName, $lName) {
    //Write to Awards Table
    $awardStatement = $mysqli->prepare("INSERT INTO fp_awards (award) VALUES (?)");
    $awardStatement->bind_param("s", $awardValue);
    $awardStatement->execute();
    $awardStatement->close();

    //Get ID of Award and bind to $awardID
    $awardIDStatement = $mysqli->prepare("SELECT id FROM fp_awards WHERE award = ?");
    $awardIDStatement->bind_param("s", $awardValue);
    $awardIDStatement->execute();
    $awardIDStatement->bind_result($awardID);
    $awardIDStatement->fetch();
    $awardIDStatement->close();

    //Get ID of Player and bind to $playerID
    $playerIDStatement = $mysqli->prepare("SELECT id FROM fp_players WHERE fname = ? AND lname = ?");
    $playerIDStatement->bind_param("ss", $fName, $lName);
    $playerIDStatement->execute();
    $playerIDStatement->bind_result($playerID);
    $playerIDStatement->fetch();
    $playerIDStatement->close();

    //Write $playerID, $awardID and $awardYear to fp_players_awards table
    $playerAwardStatement = $mysqli->prepare("INSERT INTO fp_players_awards (pid, aid, year) VALUES (?, ?, ?)");
    $playerAwardStatement->bind_param("iii", $playerID, $awardID, $awardYear);
    $playerAwardStatement->execute();
    $playerAwardStatement->close();
  }

?>

<html>
  <head>
  	<meta charset = "UTF-8">
  	<title>Add Player</title>
  </head>
  <body>
  <form action="" method="post">
    Add Player
    <br>
    <font color="red">*</font> Required Field
    <br>
    <br>
    <font color="red">*</font> First Name:
    <br>
    <input type="text" name="firstname">
    <br><br>
    <font color="red">*</font> Last Name:
    <br>
    <input type="text" name="lastname">
    <br><br>
    Position:
    <br>
    <select name= "position">
      <option value="QB">QB</option>
      <option value="RB">RB</option>
      <option value="WR">WR</option>
      <option value="TE">TE</option>
    </select>
    <br><br>
    Height:
    <br>
    Feet: 
    <select name="height_feet">
      <option value="4">4</option>
      <option value="5">5</option>
      <option value="6">6</option>
      <option value="7">7</option>
    </select>
    Inches: 
    <select name="height_inches">
      <option value="0">0</option>
      <option value="1">1</option>
      <option value="2">2</option>
      <option value="3">3</option>
      <option value="4">4</option>
      <option value="5">5</option>
      <option value="6">6</option>
      <option value="7">7</option>
      <option value="8">8</option>
      <option value="9">9</option>
      <option value="10">10</option>
      <option value="11">11</option>
    </select>
    <br><br>
    Weight:
    <br>
    <input type="text" name="weight">
    <br><br>
    Birthdate:
    <br>
    Month:
    <select name="birthmonth">
      <option value="01">01</option>
      <option value="02">02</option>
      <option value="03">03</option>
      <option value="04">04</option>
      <option value="05">05</option>
      <option value="06">06</option>
      <option value="07">07</option>
      <option value="08">08</option>
      <option value="09">09</option>
      <option value="10">10</option>
      <option value="11">11</option>
      <option value="12">12</option>
    </select>
    Day:
    <select name="birthday">
      <option value="01">01</option>
      <option value="02">02</option>
      <option value="03">03</option>
      <option value="04">04</option>
      <option value="05">05</option>
      <option value="06">06</option>
      <option value="07">07</option>
      <option value="08">08</option>
      <option value="09">09</option>
      <option value="10">10</option>
      <option value="11">11</option>
      <option value="12">12</option>
      <option value="13">13</option>
      <option value="14">14</option>
      <option value="15">15</option>
      <option value="16">16</option>
      <option value="17">17</option>
      <option value="18">18</option>
      <option value="19">19</option>
      <option value="20">20</option>
      <option value="21">21</option>
      <option value="22">22</option>
      <option value="23">23</option>
      <option value="24">24</option>
      <option value="25">25</option>
      <option value="26">26</option>
      <option value="27">27</option>
      <option value="28">28</option>
      <option value="29">29</option>
      <option value="30">30</option>
      <option value="31">31</option>
    </select>
    Year:
    <select name="birthyear">
      <option value="1980">1980</option>
      <option value="1981">1981</option>
      <option value="1982">1982</option>
      <option value="1983">1983</option>
      <option value="1984">1984</option>
      <option value="1985">1985</option>
      <option value="1986">1986</option>
      <option value="1987">1987</option>
      <option value="1988">1988</option>
      <option value="1989">1989</option>
      <option value="1990">1990</option>
      <option value="1991">1991</option>
      <option value="1992">1992</option>
      <option value="1993">1993</option>
      <option value="1994">1994</option>
      <option value="1995">1995</option>
      <option value="1996">1996</option>
      <option value="1997">1997</option>
      <option value="1998">1998</option>
      <option value="1999">1999</option>
      <option value="2000">2000</option>
    </select>
    <br><br>
    College:
    <br>
    Location (e.g. USC):<input type="text" name="collegeLocation">
    Mascot (e.g. Trojans):<input type="text" name="collegeMascot">
    <br><br>
    40 Yard Dash:
    <br>
    <input type="text" name="forty">
    <br><br>
    3-Cone Time:
    <br>
    <input type="text" name="threecone">
    <br><br>
    Shuttle:
    <br>
    <input type="text" name="shuttle">
    <br><br>
    Vertical:
    <br>
    <input type="text" name="vertical">
    <br><br>
    Broad Jump:
    <br>
    <input type="text" name="broad">
    <br><br>
    Bench Press:
    <br>
    <input type="text" name="bench">
    <br><br>
    Rotoworld.com NFL Draft Link:
    <br>
    <input type="url" name="rotoworld">
    <br><br>
    Red Flags:
    <br>
    <input type="checkbox" name="injury" value="Injury History">Injury History
    <input type="checkbox" name="character" value="Character Concerns">Character Concerns
    <br>
    Other: <input type="text" name="other">
    <br><br>
    Awards:
    <br>
    Award #1:
    <input type="text" name="award1">
    Year:
    <select name= "awardyear1">
      <option value=""></option>
      <option value="2014">2014</option>
      <option value="2013">2013</option>
      <option value="2012">2012</option>
      <option value="2011">2011</option>
      <option value="2010">2010</option>
    </select>
    <br>
    Award #2:
    <input type="text" name="award2">
    Year:
    <select name= "awardyear2">
      <option value=""></option>      
      <option value="2014">2014</option>
      <option value="2013">2013</option>
      <option value="2012">2012</option>
      <option value="2011">2011</option>
      <option value="2010">2010</option>
    </select>    
    <br>
    Award #3:
    <input type="text" name="award3">
    Year:
    <select name= "awardyear3">
      <option value=""></option>      
      <option value="2014">2014</option>
      <option value="2013">2013</option>
      <option value="2012">2012</option>
      <option value="2011">2011</option>
      <option value="2010">2010</option>
    </select>  
    <br><br>
    Possible Landing Spots:
    <br>
    <table>
    <tr>
    <td><input type="checkbox" name="landingspot[]" value="Arizona Cardinals">ARZ
    <td><input type="checkbox" name="landingspot[]" value="Atlanta Falcons">ATL
    <td><input type="checkbox" name="landingspot[]" value="Baltimore Ravens">BAL
    <td><input type="checkbox" name="landingspot[]" value="Buffalo Bills">BUF
    <td><input type="checkbox" name="landingspot[]" value="Carolina Panthers">CAR
    <td><input type="checkbox" name="landingspot[]" value="Chicago Bears">CHI
    <td><input type="checkbox" name="landingspot[]" value="Cincinnati Bengals">CIN
    <td><input type="checkbox" name="landingspot[]" value="Cleveland Browns">CLE
    <td><input type="checkbox" name="landingspot[]" value="Dallas Cowboys">DAL
    <td><input type="checkbox" name="landingspot[]" value="Denver Broncos">DEN
    <td><input type="checkbox" name="landingspot[]" value="Detroit Lions">DET
    <td><input type="checkbox" name="landingspot[]" value="Green Bay Packers">GB
    <td><input type="checkbox" name="landingspot[]" value="Houston Texans">HOU
    <td><input type="checkbox" name="landingspot[]" value="Indianapolis Colts">IND
    <td><input type="checkbox" name="landingspot[]" value="Jacksonville Jaguars">JAC
    <td><input type="checkbox" name="landingspot[]" value="Kansas City Chiefs">KC 
    <tr>
    <td><input type="checkbox" name="landingspot[]" value="Miami Dolphins">MIA
    <td><input type="checkbox" name="landingspot[]" value="Minnesota Vikings">MIN
    <td><input type="checkbox" name="landingspot[]" value="New England Patriots">NE
    <td><input type="checkbox" name="landingspot[]" value="New Orleans Saints">NO
    <td><input type="checkbox" name="landingspot[]" value="New York Giants">NYG
    <td><input type="checkbox" name="landingspot[]" value="New York Jets">NYJ
    <td><input type="checkbox" name="landingspot[]" value="Oakland Raiders">OAK
    <td><input type="checkbox" name="landingspot[]" value="Philadelphia Eagles">PHI
    <td><input type="checkbox" name="landingspot[]" value="Pittsburgh Steelers">PIT
    <td><input type="checkbox" name="landingspot[]" value="San Diego Chargers">SD
    <td><input type="checkbox" name="landingspot[]" value="Seattle Seahawks">SEA
    <td><input type="checkbox" name="landingspot[]" value="San Francisco 49ers">SF
    <td><input type="checkbox" name="landingspot[]" value="St. Louis Rams">STL
    <td><input type="checkbox" name="landingspot[]" value="Tampa Bay Buccaneers">TB
    <td><input type="checkbox" name="landingspot[]" value="Tennessee Titans">TEN
    <td><input type="checkbox" name="landingspot[]" value="Washington Redskins">WAS
    </table>
    <br><br>
    <button type="submit" name="playersubmit">Submit</button>
  </form>
  <form action="http://web.engr.oregonstate.edu/~sibailaj/finalproject/src/home.php">
    <input type="submit" value="Cancel">
  </form>
  </body>
</html>