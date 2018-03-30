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
      <li class="active"><a href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/update_query.php">Update Query</a></li>
      <li><a href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/nested_aggregation_query.php">Nested Aggregation Query</a></li>
    </ul>
  </div>
</nav>

<div class="container" style="height: 100vh">

<div class="container-fluid">
  <h2>Update Screen</h2>
</div>

<p>Update a character level</p>
<form action="update_query.php" method="POST" id="update" autocomplete="off">
<input type="number" min="1" class="form-control" name="char_id" placeholder="Enter Character ID" width="5">
<input type="number" class="form-control" name="char_level" placeholder="Enter New Character Level" width="5">
<input type="submit" value="Execute Query" class="btn btn-primary" name="update">
</form>

<?php
include("db_execute.php");

$success = True; //keep track of errors so it redirects the page only if there are no errors
$db_conn = oci_connect("ora_s4i0b", "a31112148", "dbhost.ugrad.cs.ubc.ca:1522/ug");

if ($db_conn) {

  if(array_key_exists('update', $_POST)){

      $tuple = array (
        ":bind1" => $_POST['char_id'],
        ":bind2" => $_POST['char_level']
      );
      $alltuples = array (
        $tuple
      );
      $updateQuery = "UPDATE Characters SET Char_Level = :bind2 WHERE Char_ID = :bind1";

      $statement = OCIParse($db_conn, $updateQuery);

      foreach ($alltuples as $tuple) {
        foreach ($tuple as $bind => $val) {
          //echo $val;
          //echo "<br>".$bind."<br>";
          OCIBindByName($statement, $bind, $val);
          unset ($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype

        }
        $r = OCIExecute($statement, OCI_DEFAULT);
        if (!$r) {
          echo "<h3 class ='text-danger'>Error Message</h3>";
          echo "<p class='text-danger'>Cannot execute the following command: " . $updateQuery . "</p><br>";
          if($_POST['char_level'] < 0){
            echo "<p class='text-danger'>You enter a negative number. <b>Please Enter a Positive Number</b></p><br>";
          }
          $e = OCI_Error($statement); // For OCIExecute errors pass the statementhandle
          echo htmlentities($e['message']);
          echo "<br>";
          $success = False;
        }
      }

     OCICommit($db_conn);
     if ($_POST && $success) {
        header("location: update_query.php");
     }

  }


  echo "<script>console.log( 'DB Connected' );</script>";



  echo "<br><h3>Tables for Verification of Update Query</h3>";

  $charResult = executePlainSQL("select * from Characters");
  OCICommit($db_conn);
  echo "<br>Character Table<br>";
  echo "<table class='table table-bordered'>";
  echo "<tr><th>Character Name</th><th>Character Level</th><th>Character ID</th></tr>";
  while ($row = OCI_Fetch_Array($charResult, OCI_BOTH)) {
    echo "<tr><td>" . $row["CHAR_NAME"] . "</td><td>" . $row["CHAR_LEVEL"] . "</td><td>" . $row["CHAR_ID"] . "</td></tr>";
    //echo $row[0];
  }
  echo "</table><br>";

}

?>

</div>

</body>

</html>
