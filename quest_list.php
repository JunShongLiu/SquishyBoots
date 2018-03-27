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
      <li><a href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/user.php">Character Page</a></li>
    </ul>
  </div>
</nav>

<div>

<?php
include("db_execute.php");

$success = True; //keep track of errors so it redirects the page only if there are no errors
$db_conn = OCILogon("ora_s4i0b", "a31112148", "dbhost.ugrad.cs.ubc.ca:1522/ug");

if ($db_conn) {
	echo "<script>console.log( 'DB Connected' );</script>";
	$player_id = $_SESSION['Player_ID'];
	$char_id = $_SESSION['Char_ID'];

	$questResult = executePlainSQL("SELECT Q.Q_ID, Q.q_name FROM Quest Q WHERE Q.Q_ID IN (SELECT Q2.Q_ID FROM Quest Q2 MINUS SELECT C.Q_id FROM Completes C WHERE C.Char_id = $char_id)");
	OCICommit($db_conn);

	echo "<br><h2>Choose a non-completed quest to view<h2><br>";
	echo "<table class='table table-bordered'>";
	echo "<tr> <th>Quest ID</th> <th>Quest Name</th> <th>Quest Detail</th></tr>";
	while ($row = OCI_Fetch_Array($questResult, OCI_BOTH)) {
		echo "<tr>";
		echo "<td>" . $row['Q_ID'] . "</td>";
		echo "<td>" . $row['Q_NAME'] . "</td>";
		echo "<td> <a href='quest.php?Quest_id=$row[Q_ID]'> Expand Quest</a></td>";
		echo "</tr>";
	}
	echo "</table>";

}

?> 


</div>

</body>

</html>

