<!-- 

This is the login screen

-->

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

	<div class="container" style="height: 100vh">
        <div class="text-center">
                        <form action="admin_page.php" method="POST" autocomplete="off">
                        	Delete Player
                            <div class="form-group">
                                <input type="text" class="form-control" name="player_id" width="10">
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
			echo "<script>console.log( 'Delete' );</script>";
			$player_id = $_POST['player_id'];
			$_SESSION["player_id"] = $player_id;
			$query = "delete from Player where Player_ID = $player_id";
			$result = executePlainSQL($query);
			OCICommit($db_conn);
			oci_free_statement($result);
			header('Location: http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/admin_page.php');
		}
		//Show the Character Table
		else {
			echo "<script>console.log( 'Show table' );</script>";
			$result = executePlainSql("select Username from Player");
			OCICommit($db_conn);
			
			echo "<br>Players<br>";
			echo "<table>";
			echo "<tr><th>Username</th></tr>";
			while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
				echo "<tr><td>" . $row["USERNAME"] . "</td></tr>";
				//echo $row[0];
			}
			echo "</table>";
		}
			
	}
?>
    </div>

</body>
</html>
