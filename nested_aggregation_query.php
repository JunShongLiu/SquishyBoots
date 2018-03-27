<!-- 

This is the nested aggregation screen screen

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

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/login.php">SquishyBoots</a>
    </div>
    <ul class="nav navbar-nav">
      <li class="active"><a href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/admin_page.php">Home</a></li>
      <li><a href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/division_query.php">Division Query</a></li>
      <li><a href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/join_query.php">Join Query</a></li>
      <li><a href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/update_query.php">Update Query</a></li>
      <li><a href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/nested_aggregation_query.php">Nested Aggregation Query</a></li>
      <li><a href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/login.php">Login Page</a></li>
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
<p>Find Most Active or Least Active Hero Characters</p>
<form action="nested_aggregation_query.php" method="GET" id="MaxNestedAggregationForm">
<input type="submit" value="Most Active" class="btn btn-primary" name="maxNestedAggregation">
</form>

<form action="nested_aggregation_query.php" method="GET" id="MinNestedAggregationForm">
<input type="submit" value="Least Active" class="btn btn-primary" name="minNestedAggregation">
</form>

<p>Add a quest completed by a player by supplying a player id and a quest id</p>
<form action="nested_aggregation_query.php" method="POST" id="InsertDivision" autocomplete="off">
<input type="text" class="form-control" name="player_id" placeholder="Enter Player ID" width="5">
<input type="text" class="form-control" name="quest_id" placeholder="Enter Quest ID" width="5">
<input type="submit" value="Execute Query" class="btn btn-primary" name="InsertDivision">
</form>

<?php
include("db_execute.php");

$success = True; //keep track of errors so it redirects the page only if there are no errors
$db_conn = OCILogon("ora_v0i0b", "a35223149", "dbhost.ugrad.cs.ubc.ca:1522/ug");

if ($db_conn) {
    echo "<script>console.log( 'DB Connected' );</script>";
    if(array_key_exists('maxNestedAggregation', $_GET)){
        echo "<script>console.log( 'Max Button Pressed' );</script>";

        $_SESSION["NestedAgg_Query"] = "SELECT MAX(AVG(Quests_Completed)) FROM Hero GROUP BY Player_ID";
        
        session_write_close();
        header("location: nested_aggregation_query.php");
    }
    else if(array_key_exists('minNestedAggregation', $_GET)){
	echo "<script>console.log( 'Min Button Pressed' );</script>";

	$_SESSION["NestedAgg_Query"] = "SELECT MIN(AVG(Quests_Completed)) FROM Hero GROUP BY Player_ID";

	session_write_close();
	header("location: nested_aggregation_query.php");
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
            header("location: nested_aggregation_query.php");
        }
    }
    else {

        if(isset($_SESSION["NestedAgg_Query"])){
            $query = $_SESSION['NestedAgg_Query'];
            $result = executePlainSQL($query);
            OCICommit($db_conn);

            echo "<br><h4>Result from Nested Aggregation Query</h4><br>";
            echo "<table class='table table-bordered'>";
            echo "<tr><th>Quests Completed</th></tr>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row["0"] . "</td></tr>";
                //echo $row[0];
            }
            echo "</table><br>";

            unset($_SESSION['NestedAgg_Query']);
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


