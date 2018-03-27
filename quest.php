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
<body background="pix/bg1.jpg">

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
<div class="text-center">

<?php
include("db_execute.php");

$success = True; //keep track of errors so it redirects the page only if there are no errors
$db_conn = OCILogon("ora_s4i0b", "a31112148", "dbhost.ugrad.cs.ubc.ca:1522/ug");

	if ($db_conn) {
	$quest_id;
	if(isset($_GET['Quest_id']) && !empty($_GET['Quest_id'])){
		$quest_id = $_GET['Quest_id'];
		$_SESSION["Quest_ID"] = $quest_id;
		session_write_close();
	}
	else{
		$quest_id = $_SESSION['Quest_ID'];
	}

	$questQuery = executePlainSQL("select * from Quest natural join Location where Q_ID = $quest_id");
	OCICommit($db_conn);

	while ($row = OCI_Fetch_Array($questQuery, OCI_BOTH)) {
		echo "<h2>" . $row['Q_NAME'] . "</h2>";
		echo "<p>Difficulty Level: " . $row['DIFFICULTY'] . "</p><br><br>";
		echo "<h3>" . $row['ISLAND'] . "</h3>";
		echo "<h4>" . $row['CITY'] . "</h4>";
		echo "<h5>" . $row["L_NAME"] . "</h5>";
		echo "<p>Insert Image</p><br><br>";
	}

	$enemiesQuery = executePlainSQL("select e.Char_ID, c.Char_Name, c.HP, c.MP, c.Char_Level, e.Enemy_Exp from Characters c, Enemy e, Has h where c.Char_ID = e.Char_ID and e.Char_ID = h.Enemy_id and h.Q_id = $quest_id");

	OCICommit($db_conn);
	echo "<h3>Enemies</h3><br>";
	echo "<table class='table table-bordered'>";
	echo "<tr><th>Enemy ID</th><th>Enemy Name</th><th>HP</th><th>MP</th><th>Level</th><th>Experience</th></tr>";
	while ($row = OCI_Fetch_Array($enemiesQuery, OCI_BOTH)) {
		echo "<tr><td>" . $row["CHAR_ID"] . "</td><td>" . $row["CHAR_NAME"] . "</td><td>" . $row["HP"] . "</td><td>" . $row["MP"] . "</td><td>" . $row["CHAR_LEVEL"] . "</td><td>" . $row["ENEMY_EXP"] . "</td></tr>";
		//echo $row[0];
	}
	echo "</table><br>";
	}
?>


<form action="quest.php" method="POST" id="quest">
<input type="submit" value="Complete Quest" class="btn btn-primary" name="completeQuest">
</form>

<?php

    $character_id = $_SESSION['Char_ID'];

	if ($db_conn) {
		if(array_key_exists("completeQuest", $_POST)){
			$tuple = array (
				":bind1" => $character_id,
				":bind2" => $quest_id
			);
			$alltuples = array (
				$tuple
			);
			executeBoundSQL("insert into Completes values (:bind1, :bind2)", $alltuples);
			OCICommit($db_conn);
			header("location: quest_list.php");
		}
	}

?>

</body>
</html>