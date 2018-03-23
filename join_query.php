<!-- 
This is the analysis screen
-->

<?php
	ini_set('session.save_path', './');
	session_start();
	echo session_id();
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

<div class="page-header">
	<h1>SquishyBoots</h1>
</div>

<div class="container-fluid">
<h2>Admin's Analysis Screen</h2>
</div>

<!-- Calvin's Button -->
<p>Find the heroes name, class and job of each player</p>
<form action="join_query.php" method="GET" id="JoinForm">
<input type="submit" value="Execute Query" class="btn btn-primary" name="join">
</form>

</body>
</html>

<?php
include("db_execute.php");
$success = True; //keep track of errors so it redirects the page only if there are no errors
$db_conn = oci_connect("ora_d4p0b", "a53595154", "dbhost.ugrad.cs.ubc.ca:1522/ug");
if ($db_conn) {
	echo "<script>console.log( 'DB Connected' );</script>";
	if(array_key_exists('join', $_GET)){
		//echo "<br> JOIN <br>";
		echo "<script>console.log( 'Button Pressed' );</script>";
		$_SESSION["Join_Query"] = "select player_id, char_name, hero_class, job from player p natural join hero h natural join characters c order by player_id";
		
		session_write_close();
		header("location: join_query.php");
	}
	else {
		if(isset($_SESSION["Join_Query"])){
			//echo "<br> JOIN <br>";
			$query = $_SESSION['Join_Query'];
			//$createView = executePlainSQL("create view heroCharacter (char_name, hero_class, job, player_id) AS
			//				select char_name, hero_class, job, player_id from characters c natural join hero h
			//				");
			$result = executePlainSQL("select player_id, char_name, hero_class, job from player p natural join hero h natural join characters c order by player_id");
			OCICommit($db_conn);
			echo "<br>Result from Join Query<br>";
			echo $result[1];
			echo "<table border = '1'>
			<tr>
			<th>Player ID</th>
			<th>Character Name</th>
			<th>Hero Class</th>
			<th>Job</th>
			</tr>";
			//echo "<tr><th>Name</th></tr>";
			while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
				//echo "<tr><td>" .$row["PLAYER_ID"] .", ". $row["CHAR_NAME"] ." , ". $row["HERO_CLASS"] .", ". $row["JOB"] . "</td></tr>";
				//echo "$row[0] . $row[1] . $row[2] . $row[3] . $row[4] . $row[5]<br>"; 
				echo"<tr>";
				echo"<td>" . $row['PLAYER_ID'] . "</td>";
				echo"<td>" . $row['CHAR_NAME'] . "</td>";
				echo"<td>" . $row['HERO_CLASS'] . "</td>";
				echo"<td>" . $row['JOB'] . "</td>";
				echo"</tr>";
			}
			echo"</table>";
			unset($_SESSION['Join_Query']);
		}
	}
	
}


?>
