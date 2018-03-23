<?php
	ini_set('session.save_path', './');
	session_start();
	echo session_id();
	print_r ($_SESSION);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/login.php">SquishyBoots</a>
    </div>
    <ul class="nav navbar-nav">
      <li><a href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/user.php">Character Page</a></li>
    </ul>
  </div>
</nav>

<div id = 'user'>
	    
</div>
</body>

</html>

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

	echo "<br><h2>Choose your character<h2><br>";
	echo "<table class='table table-bordered'>";
	echo "<tr> <th>Player ID</th> <th>Char ID</th> <th>Name</th> <th>Job</th> <th>Level</th> <th>More Detail</th></tr>";
	while ($row = OCI_Fetch_Array($charactersResult, OCI_BOTH)) {
		echo "<tr>";
		echo "<td>" . $row['PLAYER_ID'] . "</td>";
		echo "<td>" . $row['CHAR_ID'] . "</td>";
		echo "<td>" . $row['CHAR_NAME'] . "</td>";
		echo "<td>" . $row['JOB'] . "</td>";
		echo "<td>" . $row['CHAR_LEVEL'] . "</td>";
		echo "<td> <a href='character.php?Char_id=$row[PLAYER_ID]'> More Details</a></td>";
		echo "</tr>";
	}
	echo "</table>";

	unset($_SESSION['Agg_Query']);

	// if(array_key_exists('aggregation', $_GET)){
	// 	echo "<script>console.log( 'Button Pressed' );</script>";
	// 	$player_id = $_SESSION['Player_ID'];
	// 	$_SESSION["Agg_Query"] = "SELECT C.Char_Name, H.Job,C.Char_Level FROM Characters C, Hero H, Player P WHERE C.Char_ID = H.Char_ID AND H.Player_ID = P.Player_ID AND P.Player_ID = $player_id";
	// 	session_write_close();
	// 	header("location: user.php");
	// }
	// else {
	// 	if(isset($_SESSION["Agg_Query"])){
	// 		$char_page = 'character.php';
	// 		$query = $_SESSION['Agg_Query'];
	// 		$player_id = $_SESSION['Player_ID'];
	// 		$result = executePlainSQL($query);
	// 		OCICommit($db_conn);

	// 		echo "<br>Result from Aggregation Query<br>";
	// 		echo "<table border = '1' style = 'float: left'>";
	// 		echo "<tr> <th>Name</th> <th>Job</th> <th>Level</th> </tr>";
	// 		while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
	// 			echo "<tr>";
	// 			echo "<td>" . $row['CHAR_NAME'] . "</td>";
	// 			echo "<td>" . $row['JOB'] . "</td>";
	// 			echo "<td>" . $row['CHAR_LEVEL'] . "</td>";
	// 			echo "</tr>";
	// 		}
	// 		echo "</table>";
	// 		echo "<table border = '1' style = 'float: left'>";
	// 		echo "<tr> <th>More</th> </tr>";
	// 		echo "<tr><td><a href='character.php'>Click Here For More</a></td></tr>";
	// 		echo "<tr><td><a href='character.php'>Click Here For More</a></td></tr>";
	// 		echo "</table>";

	// 		unset($_SESSION['Agg_Query']);
	// 	}
	// }
}

?> 
