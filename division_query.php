<!-- 

This is the division screen

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
<body>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/login.php">SquishyBoots</a>
    </div>
    <ul class="nav navbar-nav">
      <li class="active"><a href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/delete.php">Delete Query</a></li>
      <li><a href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/division_query.php">Division Query</a></li>
      <li><a href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/join_query.php">Join Query</a></li>
      <li><a href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/update_query.php">Update Query</a></li>
      <li><a href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/nested_aggregation_query.php">Nested Aggregation Query</a></li>      
    </ul>
  </div>
</nav>

<div class="container" style="height: 100vh">

<div class="container-fluid">
  <h2>Division Screen</h2>
</div>

<!-- <div 
<form action="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/hello.php">
	<button type="submit" value="Submit">Submit</button>
</form>
</div> -->
<p>Find characters who completed all the quests</p>
<form action="division_query.php" method="GET" id="DivisionForm">
<input type="submit" value="Execute Query" class="btn btn-primary" name="division">
</form>

<p>Add a quest completed by a player by supplying a player id and a quest id</p>
<form action="division_query.php" method="POST" id="InsertDivision" autocomplete="off">
<input type="text" class="form-control" name="player_id" placeholder="Enter Player ID" width="5">
<input type="text" class="form-control" name="quest_id" placeholder="Enter Quest ID" width="5">
<input type="submit" value="Execute Query" class="btn btn-primary" name="InsertDivision">
</form>

<?php
include("db_execute.php");

$success = True; //keep track of errors so it redirects the page only if there are no errors
$db_conn = OCILogon("ora_s4i0b", "a31112148", "dbhost.ugrad.cs.ubc.ca:1522/ug");

if ($db_conn) {
	echo "<script>console.log( 'DB Connected' );</script>";
	if(array_key_exists('division', $_GET)){
		echo "<script>console.log( 'Button Pressed' );</script>";

		$_SESSION["Div_Query"] = "select Ch.Char_Name from Characters Ch where not exists ((select Q.Q_ID from Quest Q) minus (select C.Q_id from Completes C where Ch.Char_ID = C.Char_id))";
		
		session_write_close();
		header("location: division_query.php");
	}
	else if(array_key_exists("InsertDivision", $_POST)){
		$tuple = array (
				":bind1" => $_POST['player_id'],
				":bind2" => $_POST['quest_id']
		);
		$alltuples = array (
			$tuple
		);
		executeBoundSQL("insert into Completes values (:bind1, :bind2)", $alltuples);
		OCICommit($db_conn);
		if ($_POST && $success) {
			header("location: division_query.php");
		}
	}
	else {

		if(isset($_SESSION["Div_Query"])){
			$query = $_SESSION['Div_Query'];
			$result = executePlainSQL("select Ch.Char_Name from Characters Ch where not exists ((select Q.Q_ID from Quest Q) minus (select C.Q_id from Completes C where Ch.Char_ID = C.Char_id))");
			OCICommit($db_conn);

			echo "<br><h4>Result from Division Query</h4><br>";
			echo "<table class='table table-bordered'>";
			echo "<tr><th>Name</th></tr>";
			while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
				echo "<tr><td>" . $row["CHAR_NAME"] . "</td></tr>";
				//echo $row[0];
			}
			echo "</table><br>";

			unset($_SESSION['Div_Query']);
		}

		echo "<br><h3>Tables for Verification of Division Query</h3>";

		$questResult = executePlainSQL("select * from Quest");
		OCICommit($db_conn);
		echo "<br>Quest Table<br>";
		echo "<table class='table table-bordered'>";
		echo "<tr><th>Quest ID</th><th>Quest Name</th><th>Location ID</th><th>Difficulty</th></tr>";
		while ($row = OCI_Fetch_Array($questResult, OCI_BOTH)) {
			echo "<tr><td>" . $row["Q_ID"] . "</td><td>" . $row["Q_NAME"] . "</td><td>" . $row["LOC_ID"] . "</td><td>" . $row["DIFFICULTY"] . "</td></tr>";
			//echo $row[0];
		}
		echo "</table><br>";

		$charResult = executePlainSQL("select * from Characters");
		OCICommit($db_conn);
		echo "<br>Quest Table<br>";
		echo "<table class='table table-bordered'>";
		echo "<tr><th>Character Name</th><th>Character Level</th><th>Character ID</th></tr>";
		while ($row = OCI_Fetch_Array($charResult, OCI_BOTH)) {
			echo "<tr><td>" . $row["CHAR_NAME"] . "</td><td>" . $row["CHAR_LEVEL"] . "</td><td>" . $row["CHAR_ID"] . "</td></tr>";
			//echo $row[0];
		}
		echo "</table><br>";

		$charResult = executePlainSQL("select * from Completes");
		OCICommit($db_conn);
		echo "<br>Character Complete Quest Table<br>";
		echo "<table class='table table-bordered'>";
		echo "<tr><th>Character ID</th><th>Quest ID</th></tr>";
		while ($row = OCI_Fetch_Array($charResult, OCI_BOTH)) {
			echo "<tr><td>" . $row["CHAR_ID"] . "</td><td>" . $row["Q_ID"] . "</td></tr>";
			//echo $row[0];
		}
		echo "</table><br>";
	}
}

?>

</div>

</body>
</html>

