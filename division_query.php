<!-- 

This is the division screen

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

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">SquishyBoots</a>
    </div>
    <ul class="nav navbar-nav">
      <li class="active"><a href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/admin_page.php">Home</a></li>
      <li><a href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/division_query.php">Division Query</a></li>
      <li><a href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/join_query.php">Join Query</a></li>
      <li><a href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/login2.php">Login Page</a></li>
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

<?php
include("db_execute.php");

$success = True; //keep track of errors so it redirects the page only if there are no errors
$db_conn = oci_connect("ora_s4i0b", "a31112148", "dbhost.ugrad.cs.ubc.ca:1522/ug");

if ($db_conn) {
	echo "<script>console.log( 'DB Connected' );</script>";
	if(array_key_exists('division', $_GET)){
		echo "<script>console.log( 'Button Pressed' );</script>";

		$_SESSION["Div_Query"] = "select Ch.Char_Name from Characters Ch where not exists ((select Q.Q_ID from Quest Q) minus (select C.Q_id from Completes C where Ch.Char_ID = C.Char_id))";
		
		session_write_close();
		header("location: division_query.php");
	}
	else {
		if(isset($_SESSION["Div_Query"])){
			$query = $_SESSION['Div_Query'];
			$result = executePlainSQL("select Ch.Char_Name from Characters Ch where not exists ((select Q.Q_ID from Quest Q) minus (select C.Q_id from Completes C where Ch.Char_ID = C.Char_id))");
			OCICommit($db_conn);

			echo "<br>Result from Division Query<br>";
			echo "<table>";
			echo "<tr><th>Name</th></tr>";
			while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
				echo "<tr><td>" . $row["CHAR_NAME"] . "</td></tr>";
				//echo $row[0];
			}
			echo "</table>";

			unset($_SESSION['Div_Query']);
		}
	}
}

?>

</div>>

</body>
</html>

?>