<?php
	ini_set('session.save_path', './');
	session_start();
	echo "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script> $(function() {
		$("#text-one").change(function() {
		    $("#text-two").load("textdata/" + $(this).val() + ".txt");
		    $("#text-three").load("textdata/" + $(this).val() + ".txt");
	   	});
	   });</script>
</head>
<body background="pix/bg1.jpg">


<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/login.php">SquishyBoots</a>
    </div>
    <ul class="nav navbar-nav">
      <li><a href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/user.php">Character Page</a></li>
    </ul>
	<div style = 'float:right;'>
    <form action="user.php" method="POST" id="Logout" class ="navbar-nav">
<input type="submit" value="Log Out" class="btn btn-primary" name="logout"></div>
</form> </div>

  </div>
</nav>


<form action="user.php" method="POST" id="CreateHero">

    Character Name: <input type="text" name="charname" maxlength="20" pattern="[:*,*(*)*!*@*.*a-zA-Z0-9 ]+" required><br>
    <div class="col-xs-3">
Class: 
<select name="table" class="form-control" id="text-one" required>
<option value="">Please Select</option>
<option value="Magician">Magician</option>
<option value="Bowman">Bowman</option>
<option value="Pirate">Pirate</option>
<option value="Thief">Thief</option>
<option value="Warrior">Warrior</option>
</select> 
Job: 
<select name="job" class="form-control" size="6" id="text-two" required><option>Please choose from above</option></select><br>
</select> 

<br> 

<input type="submit"  value="Create Hero" class="btn btn-primary" name="createhero">
</form>


<br> 
<br> 
<br> 
<br>

<form action="user.php" method="POST" id="DeleteHero">
Character ID: <input type="number" class="form-control"  min= "1" name="Char_ID" maxlength="20" required><br>
<input type="submit" value="Delete Hero" class="btn btn-primary" name="deletehero">
</form></div>
<br> 
<br> 
<br> 
<br> 

<br> 
<br> 
<br> 
<br> 
<br> 
<br> 
<br> 
<br> 
<br> 

<br> 
<br> 
<br> 
<br> 


<br> 
<br> 
<br> 
<br> 
<br> 

<?php
include("db_execute.php");

$success = True; //keep track of errors so it redirects the page only if there are no errors
$db_conn = OCILogon("ora_s4i0b", "a31112148", "dbhost.ugrad.cs.ubc.ca:1522/ug");

if ($db_conn) {
	echo "<script>console.log( 'DB Connected' );</script>";
	$player_id = $_SESSION['Player_ID'];

	echo "<script>console.log( 'Player_ID' + $player_id );</script>";

	$charactersResult = executePlainSQL("SELECT H.Player_ID, C.Char_ID, C.Char_Name, H.Job,C.Char_Level FROM Characters C, Hero H, Player P WHERE C.Char_ID = H.Char_ID AND H.Player_ID = P.Player_ID AND P.Player_ID = $player_id");
	OCICommit($db_conn);

	echo "<br><h2>Choose your character</h2><br>";
	echo "<table class='table table-bordered'>";
	echo "<tr> <th>Player ID</th> <th>Char ID</th> <th>Name</th> <th>Job</th> <th>Level</th> <th>More Detail</th></tr>";
	while ($row = OCI_Fetch_Array($charactersResult, OCI_BOTH)) {
		echo "<tr>";
		echo "<td>" . $row['PLAYER_ID'] . "</td>";
		echo "<td>" . $row['CHAR_ID'] . "</td>";
		echo "<td>" . $row['CHAR_NAME'] . "</td>";
		echo "<td>" . $row['JOB'] . "</td>";
		echo "<td>" . $row['CHAR_LEVEL'] . "</td>";
		echo "<td> <a href='character.php?Char_id=$row[CHAR_ID]'> More Details</a></td>";
		echo "</tr>";
	}
	echo "</table>";


	if (array_key_exists("createhero", $_POST)){

		$charID = rand(15, 1000);   // number of total characters +1  change** 
		$charname = $_POST['charname'];
		$job = $_POST['job'];
		$class = $_POST['table'];
		$playerID = $_SESSION['Player_ID'];
		$hp = 100;
		$mp = 100;
		$quests = 1;
		$level = 10;

		$charquery = "insert into Characters values($hp, $mp, '$charname', $level, $charID)";
		executePlainSQL($charquery);
		OCICommit($db_conn);
		$heroquery = "insert into Hero values('$class', '$job', $quests, $playerID, $charID)";
		executePlainSQL($heroquery);
		OCICommit($db_conn);
		header("location: user.php");

		}

	else if (array_key_exists("deletehero", $_POST)){
		
		if(!isset($_POST['Char_ID'])){
			died('Form is not complete.');
		}else{
            $tuple = array (
                ":bind1" => $_POST['Char_ID']
			);
			
            $alltuples = array (
                $tuple
            );
            executeBoundSQL("delete from Characters where Char_id = :bind1", $alltuples);
            OCICommit($db_conn);
            header("location: user.php");
		}
		
	}else if (array_key_exists("logout", $_POST)){
		session_destroy();
		header("location: login.php");
	}
}

?> 

</body>

</html>