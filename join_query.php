<!-- 
This is the analysis screen
-->

<?php
	ini_set('session.save_path', './');
	session_start();
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
<body background="pix/bg2.jpg">


<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/login.php">SquishyBoots</a>
    </div>
    <ul class="nav navbar-nav">
      <li><a href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/projection_selection_query.php">Projection & Selection Query</a></li>
      <li><a href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/delete.php">Delete Query</a></li>
      <li><a href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/division_query.php">Division Query</a></li>
      <li class="active"><a href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/join_query.php">Join Query</a></li>
      <li><a href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/update_query.php">Update Query</a></li>
      <li><a href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/nested_aggregation_query.php">Nested Aggregation Query</a></li>
    </ul>
  </div>
</nav>

<div class="container" style="height: 100vh">

<div class="container-fluid">
  <h2>Join Query Screen</h2>
</div>

<!-- Calvin's Button -->
<p>Find the heroes name, class and job of each player</p>
<form action="join_query.php" method="GET" id="HeroForm">
<input type="submit" value="Execute Query" class="btn btn-primary" name="hero">
</form>

<p>Find enemy type of each quest</p>
<form action="join_query.php" method="GET" id="EnmyForm">
<input type="submit" value="Execute Query" class="btn btn-primary" name="enmy">
</form>

</body>
</html>

<?php
include("db_execute.php");
$success = True; //keep track of errors so it redirects the page only if there are no errors
$db_conn = OCILogon("ora_s4i0b", "a31112148", "dbhost.ugrad.cs.ubc.ca:1522/ug");
if ($db_conn) {
	echo "<script>console.log( 'DB Connected' );</script>";
	if(array_key_exists('hero', $_GET)){
		//echo "<br> JOIN <br>";
		echo "<script>console.log( 'Button Pressed' );</script>";
		$_SESSION["Hero_Query"] = "select player_id, char_name, hero_class, job from player p natural join hero h natural join characters c order by player_id";
		
		session_write_close();
		header("location: join_query.php");
	}
	else if (array_key_exists('enmy', $_GET)){
		echo "<script>console.log( 'Button Pressed' );</script>";
		$_SESSION["Enmy_Query"] = "select q.q_id, q.q_name, c.char_name, e.enemy_exp from (((quest q inner join has h on q.q_id = h.q_id) inner join characters c on h.enemy_id = c.char_id) inner join enemy e on e.char_id = c.char_id) order by q.q_id";
		
		session_write_close();
		header("location: join_query.php");
	}
	else {
		if(isset($_SESSION["Hero_Query"])){
			//echo "<br> JOIN <br>";
			$query = $_SESSION['Hero_Query'];
			//$createView = executePlainSQL("create view heroCharacter (char_name, hero_class, job, player_id) AS
			//				select char_name, hero_class, job, player_id from characters c natural join hero h
			//				");
		
			$hero = executePlainSQL("select player_id, char_name, hero_class, job from player p natural join hero h natural join characters c order by player_id");
			OCICommit($db_conn);
			
			echo "<br><h3>Characters<h3><br>";
			//echo $result[1];
			echo "<table class = 'table table-bordered'>
			<tr>
			<th>Player ID</th>
			<th>Character Name</th>
			<th>Hero Class</th>
			<th>Job</th>
			</tr>";
			//echo "<tr><th>Name</th></tr>";
			while ($row = OCI_Fetch_Array($hero, OCI_BOTH)) {
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
			unset($_SESSION['Hero_Query']);
		}

		if(isset($_SESSION["Enmy_Query"])){
			//echo "<br> JOIN <br>";
			$query2 = $_SESSION['Enmy_Query'];
			//$createView = executePlainSQL("create view heroCharacter (char_name, hero_class, job, player_id) AS
			//				select char_name, hero_class, job, player_id from characters c natural join hero h
			//				");
		
			$enemy = executePlainSQL("select q.q_id, q.q_name, c.char_name, e.enemy_exp from (((quest q inner join has h on q.q_id = h.q_id) inner join characters c on h.enemy_id = c.char_id) inner join enemy e on e.char_id = c.char_id) order by q.q_id");
			OCICommit($db_conn);
			
			echo "<br><h3>Enemies<h3><br>";
			//echo $result[1];
			echo "<table class = 'table table-bordered'>
			<tr>
			<th>Quest ID</th>
			<th>Quest Name</th>
			<th>Enemy Name</th>
			<th>Exp</th>
			</tr>";
			//echo "<tr><th>Name</th></tr>";
			while ($row = OCI_Fetch_Array($enemy, OCI_BOTH)) {
				//echo "<tr><td>" .$row["PLAYER_ID"] .", ". $row["CHAR_NAME"] ." , ". $row["HERO_CLASS"] .", ". $row["JOB"] . "</td></tr>";
				//echo "$row[0] . $row[1] . $row[2] . $row[3] . $row[4] . $row[5]<br>"; 
				echo"<tr>";
				echo"<td>" . $row['Q_ID'] . "</td>";
				echo"<td>" . $row['Q_NAME'] . "</td>";
				echo"<td>" . $row['CHAR_NAME'] . "</td>";
				echo"<td>" . $row['ENEMY_EXP'] . "</td>";
				echo"</tr>";
			}
			echo"</table>";
			unset($_SESSION['Enmy_Query']);
		}		

		$player = executePlainSQL("select * from player order by player_id");
		$hero = executePlainSQL("select * from hero order by player_id");
		$character = executePlainSQL("select * from characters order by char_id");
		$enemy = executePlainSQL("select * from enemy order by char_id");
		$char_id = array();
		$enemy_id = array();

		echo "<br><h3>Tables for Verification of Join Query</h3>";
		
		// Display entries of player
		echo "<br>Table of Players<br>";
		echo "<table class='table table-bordered' border = '1'>";
		echo '<tr>
		<th> Username </th>
		<th> E-mail </th>
		<th style="background-color:#D1F2EB"> Player ID </th>'
		.'</tr>';
		while ($row = OCI_Fetch_Array($player, OCI_BOTH)) {
			echo "<tr>";
			echo "<td>" . $row['USERNAME'] . "</td>";
			echo "<td>" . $row['EMAIL'] . "</td>";
			echo '<td style="background-color:#D1F2EB">' . $row['PLAYER_ID'] . '</td>';
			echo '</tr>';
		}
		echo "</table>";

		// Display entries of hero
		echo "<br>Table of Heroes<br>";
		echo "<table class='table table-bordered' border = '1'>";
		echo '<tr>
		<th style="background-color:#D1F2EB"> Player ID </th>
		<th> Hero Class </th>
		<th> Job </th>
		<th> Events Completed </th>
		<th style="background-color:#FCF3CF"> Character ID </th>'.'</tr>';
		while ($row = OCI_Fetch_Array($hero, OCI_BOTH)) {
			echo "<tr>";
			echo '<td style="background-color:#D1F2EB">' . $row['PLAYER_ID'] . "</td>";
			echo "<td>" . $row['HERO_CLASS'] . "</td>";
			echo "<td>" . $row['JOB'] . "</td>";
			echo "<td>" . $row['QUESTS_COMPLETED'] . "</td>";
			echo '<td style="background-color:#FCF3CF">' . $row['CHAR_ID'] . "</td>";
			array_push($char_id, $row['CHAR_ID']);
			echo "</tr>";
		}
		echo "</table>";

		// Display entries of enemies
		echo "<br>Table of Enemies<br>";
		echo "<table class='table table-bordered' border = '1'>";
		echo '<tr>
		<th style="background-color:#EC7063"> Enemy ID </th>
		<th> Enemy EXP </th>'.'</tr>';
		while ($row = OCI_Fetch_Array($enemy, OCI_BOTH)) {
			echo "<tr>";
			echo '<td style="background-color:#EC7063">' . $row['CHAR_ID'] . "</td>";
			array_push($enemy_id, $row['CHAR_ID']);
			echo "<td>" . $row['ENEMY_EXP'] . "</td>";
			echo "</tr>";
		}
		echo "</table>";

		// Display entries of characers
		//echo "$char_id[0] . $char_id[1] . $char_id[2] . $char_id[3]"; 
		echo "<br>Table of Characters<br>";
		echo "<table class='table table-bordered' border = '1'>";
		echo '<tr>
		<th style="background-color:#FCF3CF"> Character ID </th>
		<th> HP </th>
		<th> MP </th>
		<th> Character Name </th>
		<th> Level </th>' . '</tr>';
		while ($row = OCI_Fetch_Array($character, OCI_BOTH)) {
			echo "<tr>";
			if(in_array($row['CHAR_ID'], $char_id, true)){
			echo '<td style="background-color:#FCF3CF">' . $row['CHAR_ID'] . "</td>";
			} else { echo '<td style="background-color:#EC7063">' . $row['CHAR_ID'] . "</td>"; }
			echo "<td>" . $row['HP'] . "</td>";
			echo "<td>" . $row['MP'] . "</td>";
			echo "<td>" . $row['CHAR_NAME'] . "</td>";
			echo "<td>" . $row['CHAR_LEVEL'] . "</td>";
			echo "</tr>";
		}
		echo "</table>";
	}
	echo "<br>";
	
}


?>
