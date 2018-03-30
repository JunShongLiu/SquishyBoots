<!-- 

This is the delete screen

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
<body background='pix/bg2.jpg'>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/login.php">SquishyBoots</a>
    </div>
    <ul class="nav navbar-nav">
      <li><a href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/projection_selection_query.php">Projection & Selection Query</a></li>
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
  		<h2>Delete Query Screen</h2>
	</div>
        <div class="text-center">
                        <form action="delete.php" method="POST" autocomplete="off">
                        	Delete Player
                            <div class="form-group">
                                <input type="number" min='1' class="form-control" name="player_id" placeholder="Enter Player ID" width="10">
                            </div>
                            <input type="submit" id="deletePlayer" value="Delete" name="delete" class="btn btn-primary">
                        </form>
        </div>
		<?php
		include("db_execute.php");
		$success = True; //keep track of errors so it redirects the page only if there are no errors
		$db_conn = oci_connect("ora_s4i0b", "a31112148", "dbhost.ugrad.cs.ubc.ca:1522/ug");
		if($db_conn){

		echo "<script>console.log( 'DB Connected' );</script>";
		if(array_key_exists("delete", $_POST)){
			$player_id = $_POST['player_id'];
			$_SESSION["player_id"] = $player_id;
			$query = "delete from Player where Player_ID = $player_id";
			$result = executePlainSQL($query);
			OCICommit($db_conn);
			oci_free_statement($result);
			header('Location: http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/delete.php');
		}
		//Show the Character Table
		else {
			$result = executePlainSql("select * from Player");
			OCICommit($db_conn);
			
			echo "<br>Players<br>";
			echo "<table class='table table-bordered'>";
			echo "<tr><th>Username</th><th>Email</th><th>Player_ID</th></tr>";
			while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
				echo "<tr><td>" . $row["USERNAME"] . "</td>";
				echo "<td>" . $row["EMAIL"] . "</td>";
				echo "<td>" . $row["PLAYER_ID"] . "</td></tr>";
				//echo $row[0];
			}
			echo "</table>";

			$result = executePlainSql("select * from Hero");
			OCICommit($db_conn);
			
			echo "<br>Heroes<br>";
			echo "<table class='table table-bordered'>";
			echo "<tr><th>Hero Class</th><th>Job</th><th>Events Completed</th><th>Player ID</th><th>Char ID</th></tr>";
			while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
				echo "<tr><td>" . $row["HERO_CLASS"] . "</td>";
				echo "<td>" . $row["JOB"] . "</td>";
				echo "<td>" . $row["QUEST_COMPLETED"] . "</td>";
				echo "<td>" . $row["PLAYER_ID"] . "</td>";
				echo "<td>" . $row["CHAR_ID"] . "</td></tr>";
				//echo $row[0];
			}
			echo "</table>";
		}
			
	}
?>
    </div>

</body>
</html>
