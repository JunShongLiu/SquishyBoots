<!-- 

This is the Projection and Selection Screen

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
  <script> $(function() {
		$("#text-one").change(function() {
		    $("#text-two").load("textdata/" + $(this).val() + ".txt");
		    $("#text-three").load("textdata/" + $(this).val() + ".txt");
	   	});
	   });</script>
</head>
<body background='pix/bg2.jpg'>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/login.php">SquishyBoots</a>
    </div>
    <ul class="nav navbar-nav">
      <li class="active"><a href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/projection_selection_query.php">Projection & Selection Query</a></li>
      <li><a href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/delete.php">Delete Query</a></li>
      <li><a href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/division_query.php">Division Query</a></li>
      <li><a href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/join_query.php">Join Query</a></li>
      <li><a href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/update_query.php">Update Query</a></li>
      <li><a href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/nested_aggregation_query.php">Nested Aggregation Query</a></li>      
    </ul>
  </div>
</nav>

<div class="container" style="height: 100vh">

<div class="container-fluid">
	<h2>Projection and Selection</h2>
</div>

<!-- <div 
<form action="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/hello.php">
	<button type="submit" value="Submit">Submit</button>
</form>
</div> -->
<table border="0">

<h3> Projection </h3>

<form action="projection_selection_query.php" method="POST" id='projection'>
Table: 
<select name="table" class="form-control" id="text-one" required>
    <option selected="" value="base">Please Select</option>
    <option value="Player">Player</option>
    <option value="Location">Location</option>
    <option value="Quest">Quest</option>
    <option value="Characters">Characters</option>
    <option value="Enemy">Enemy</option>
    <option value="Hero">Hero</option>
    <option value="Item">Item</option>
    <option value="Carries">Carries</option>
    <option value="Completes">Completes</option>
    <option value="Has">Has</option>
</select><br>
Columns:
    <select name="columns[]" class="form-control" multiple size="5" id="text-two" required><option>Please choose from above</option></select><br>

<h3> Selection </h3>
Selection: <select name="row" class="form-control" size="5" id="text-three"><option>Please choose from above</option></select><br>
Value: <input type="text" class="form-control" name="value" pattern="[:*,*(*)*!*@*.*a-zA-Z0-9 ]+"><br>
<td colspan="2" align="center">
<input type="submit" value="Execute Projection" class="btn btn-primary" name="projection">
<br><br>
<input type="submit" value="Execute Selection" class="btn btn-primary" name="selection">
</form>
<br><br>
<form methods="POST" action="projection_selection_query.php">
<p><input type="submit" value="Reset" class="btn btn-primary" name="reset"></p> 
</form>
</table>

<?php
include("db_execute.php");

$success = True; //keep track of errors so it redirects the page only if there are no errors
$db_conn = OCILogon("ora_s4i0b", "a31112148", "dbhost.ugrad.cs.ubc.ca:1522/ug");

if ($db_conn){
    echo "<script>console.log( 'DB Connected' );</script>";
    if(array_key_exists('projection', $_POST)){
	echo "<script>console.log( 'Projection Button Pressed' );</script>";

	$table = $_POST['table'];
	$columns = $_POST['columns'];
	$selected_columns = implode(', ', $columns);
	
        $_SESSION["Query"] = "select $selected_columns from $table";
        $_SESSION["Columns"] = $columns;
        session_write_close();
        header("location: projection_selection_query.php");
    }
    else if(array_key_exists('selection', $_POST)) {
	echo "<script>console.log( 'Selection Button Pressed' );</script>";
	$table = $_POST['table'];
	$columns = $_POST['columns'];
	$selected_columns = implode(', ', $columns);
	$row = $_POST['row'];
	$value = $_POST['value'];
	
	if (!is_numeric($value)) {
	    $_SESSION["Query"] = "select $selected_columns from $table where $row = '$value'";
	} else {
	    $_SESSION["Query"] = "select $selected_columns from $table where $row = $value";
	}
	$_SESSION["Columns"] = $columns;
	session_write_close();
	header("location: projection_selection_query.php");
    }
    else {
        if(isset($_SESSION["Query"])){
            $query = $_SESSION['Query'];
	    $columns = $_SESSION['Columns'];
	    echo $query;
            $result = executePlainSQL($query);
            OCICommit($db_conn);

            echo "<br><h4>Result from Projection Query</h4><br>";
            echo "<table class='table table-bordered'>";
            echo "<tr>";
	    foreach($columns as $column){
		echo "<th>" . $column . "</th>";
	    }
	    echo "</tr>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
		echo "<tr>";
		for($i=0; $i<sizeof($columns); $i++) {
		    echo "<td>" . $row["$i"] . "</td>";
		}
		echo "</tr>";
                //echo $row[0];
            }
            echo "</table><br>";
	    
	    unset($_SESSION['Query']);
	    unset($_SESSION['Columns']);
        }
    }
}
?>

</div>

</body>
</html>

