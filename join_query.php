<!-- 
This is the analysis screen
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

<div class="page-header">
	<h1>SquishyBoots</h1>
</div>

<div class="container-fluid">
  <h2>Admin's Analysis Screen</h2>
</div>

<!-- Calvin's Button -->
<p>Find the location where quest(s) of difficulty 0 is held</p>
<form action="analysis_screen.php" method="GET" id="JoinForm">
<input type="submit" value="Execute Query" class="btn btn-primary" name="join">
</form>
