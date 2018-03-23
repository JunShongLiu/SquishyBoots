<?php
	ini_set('session.save_path', './');
	session_start();
	echo session_id();
	print_r ($_SESSION);
	echo "Favorite color is " . $_SESSION['Player_ID'] . ".<br>";
?>

<html>
    <head>
        <title>PHP Test</title>
    </head>
    <body>
	<div id = 'user">
	    <p id = 'login_existing"> Hello Existing User : 
		<i> <?php echo $_SESSION['username']; ?> </i> </p>
	    <p> Find count of number of characters this player has:  </p>
	    <form action="user.php" method="GET" id="AggregationForm">
	    <input type="submit" value="Execute Query"  name="aggregation">
	    </form>
	    <p> <a href = "login.php"> <button type = "button"> Logout </button> </a> </p>
	</div>
    </body>
</html>

<?php
include("db_execute.php");

$success = True; //keep track of errors so it redirects the page only if there are no errors
$db_conn = OCILogon("ora_s4i0b", "a31112148", "dbhost.ugrad.cs.ubc.ca:1522/ug");

if ($db_conn) {
	echo "<script>console.log( 'DB Connected' );</script>";
	if(array_key_exists('aggregation', $_GET)){
		echo "<script>console.log( 'Button Pressed' );</script>";
		$player_id = $_SESSION['Player_ID'];
		$_SESSION["Agg_Query"] = "SELECT C.Char_Name, H.Job,C.Char_Level FROM Characters C, Hero H, Player P WHERE C.Char_ID = H.Char_ID AND H.Player_ID = P.Player_ID AND P.Player_ID = $player_id";
		session_write_close();
		header("location: user.php");
	}
	else {
		if(isset($_SESSION["Agg_Query"])){
			$char_page = 'character.php';
			$query = $_SESSION['Agg_Query'];
			$player_id = $_SESSION['Player_ID'];
			$result = executePlainSQL($query);
			OCICommit($db_conn);

			echo "<br>Result from Aggregation Query<br>";
			echo "<table border = '1' style = 'float: left'>";
			echo "<tr> <th>Name</th> <th>Job</th> <th>Level</th> </tr>";
			while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
				echo "<tr>";
				echo "<td>" . $row['CHAR_NAME'] . "</td>";
				echo "<td>" . $row['JOB'] . "</td>";
				echo "<td>" . $row['CHAR_LEVEL'] . "</td>";
				echo "</tr>";
			}
			echo "</table>";
			echo "<table border = '1' style = 'float: left'>";
			echo "<tr> <th>More</th> </tr>";
			echo "<tr><td><a href='character.php'>Click Here For More</a></td></tr>";
			echo "<tr><td><a href='character.php'>Click Here For More</a></td></tr>";
			echo "</table>";

			unset($_SESSION['Agg_Query']);
		}
	}
}

?> 
