<!-- 

This is the nested aggregation screen screen

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
      <li><a href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/delete.php">Delete Query</a></li>
      <li><a href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/division_query.php">Division Query</a></li>
      <li><a href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/join_query.php">Join Query</a></li>
      <li><a href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/update_query.php">Update Query</a></li>
      <li class="active"><a href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/nested_aggregation_query.php">Nested Aggregation Query</a></li>
    </ul>
  </div>
</nav>

<div class="container" style="height: 100vh">

<div class="container-fluid">
  <h2>Nested Aggregation Screen</h2>
</div>

<!-- <div 
<form action="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/hello.php">
    <button type="submit" value="Submit">Submit</button>
</form>
</div> -->
<p>Find Most Active or Least Active Player Based On Their Average of Events Completed</p>
<form action="nested_aggregation_query.php" method="GET" id="MaxNestedAggregationForm">
<input type="submit" value="Most Active" class="btn btn-primary" name="maxNestedAggregation">
</form>

<br>

<form action="nested_aggregation_query.php" method="GET" id="MinNestedAggregationForm">
<input type="submit" value="Least Active" class="btn btn-primary" name="minNestedAggregation">
</form>

<br>

<p>Find Player With the Most Events Completed Across All Their Characters</p>
<form action="nested_aggregation_query.php" method="GET" id="OtherNestedAggregationForm">
<input type="submit" value="Most Events Completed" class="btn btn-primary" name="otherNestedAggregation">
</form>



<?php
include("db_execute.php");

$success = True; //keep track of errors so it redirects the page only if there are no errors
$db_conn = OCILogon("ora_s4i0b", "a31112148", "dbhost.ugrad.cs.ubc.ca:1522/ug");

if ($db_conn) {
    echo "<script>console.log( 'DB Connected' );</script>";
    if(array_key_exists('maxNestedAggregation', $_GET)){
        echo "<script>console.log( 'Max Button Pressed' );</script>";

        $_SESSION["NestedAgg_Query"] = "select Player_ID, Username, Agg from (select P.player_id, P.Username, avg(Quests_Completed) as Agg from hero H, player P where H.Player_ID = P.Player_ID group by P.player_id, P.Username) Agg where Agg = (SELECT MAX(AVG(Quests_Completed)) FROM Hero GROUP BY Player_ID)";
        
        session_write_close();
        header("location: nested_aggregation_query.php");
    }
    else if(array_key_exists('minNestedAggregation', $_GET)){
		echo "<script>console.log( 'Min Button Pressed' );</script>";

		$_SESSION["NestedAgg_Query"] = "select Player_ID, Username, Agg from (select P.player_id, P.Username, avg(Quests_Completed) as Agg from hero H, player P where H.Player_ID = P.Player_ID group by P.player_id, P.Username) Agg where Agg = (SELECT MIN(AVG(Quests_Completed)) FROM Hero GROUP BY Player_ID)";

		session_write_close();
		header("location: nested_aggregation_query.php");
    }
	else if(array_key_exists('otherNestedAggregation', $_GET)){
		echo "<script>console.log( 'Max Button Pressed' );</script>";
		
		$_SESSION["NestedAgg_Query"] = "select Player_ID, Username, Agg from (select P.player_id, P.Username, sum(Quests_Completed) as Agg from hero H, player P where H.Player_ID = P.Player_ID group by P.player_id, P.Username) Agg where Agg = (SELECT MAX(SUM(Quests_Completed)) FROM Hero GROUP BY Player_ID)";
		
		session_write_close();
		header("location: nested_aggregation_query.php");
	}
    else {

        if(isset($_SESSION["NestedAgg_Query"])){
            $query = $_SESSION['NestedAgg_Query'];
            $result = executePlainSQL($query);
            OCICommit($db_conn);

            echo "<br><h4>Result from Nested Aggregation Query</h4><br>";
			echo "$query";
            echo "<table class='table table-bordered'>";
            echo "<tr><th>Player_ID</th><th>Player Name</th><th>Average Events Completed</th></tr>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row["0"] . "</td><td>" . $row["1"] . "</td><td>" . $row["2"] . "</td></tr>";
				//echo $row[0];
            }
            echo "</table><br>";

            unset($_SESSION['NestedAgg_Query']);
        }

        echo "<br><h3>Tables for Verification of Nested Aggregation Query</h3>";

        $avgResult = executePlainSQL("select P.player_id, P.Username, avg(Quests_Completed) from hero H, player P where H.Player_ID = P.Player_ID group by P.player_id, P.Username");
        OCICommit($db_conn);
        echo "<br>Average Events Completed Table<br>";
        echo "<table class='table table-bordered'>";
        echo "<tr><th>Player ID</th><th>Player Name</th><th>Average Events Completed</th></tr>";
        while ($row = OCI_Fetch_Array($avgResult, OCI_BOTH)) {
            echo "<tr><td>" . $row["0"] . "</td><td>" . $row["1"] . "</td><td>" . $row["2"] . "</td></tr>";
            //echo $row[0];
        }
        echo "</table><br>";

        $charResult = executePlainSQL("select Player_ID, Char_ID, Quests_Completed from Hero");
        OCICommit($db_conn);
        echo "<br>Hero Table<br>";
        echo "<table class='table table-bordered'>";
        echo "<tr><th>Player ID</th><th>Character ID</th><th>Events Completed</th></tr>";
        while ($row = OCI_Fetch_Array($charResult, OCI_BOTH)) {
            echo "<tr><td>" . $row["PLAYER_ID"] . "</td><td>" . $row["CHAR_ID"] . "</td><td>" . $row["QUESTS_COMPLETED"] . "</td></tr>";
            //echo $row[0];
        }
        echo "</table><br>";
    }
}

?>

</div>

</body>
</html>


