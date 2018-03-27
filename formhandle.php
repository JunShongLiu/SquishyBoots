
<?php
ini_set('session.save_path', './');
session_start();
?>

<DOCTYPE html>
<html>
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
    <a class="navbar-brand" href="#">SquishyBoots</a>
  </div>
  <ul class="nav navbar-nav">
    <li class="active"><a href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/admin_page.php">Home</a></li>
    <li><a href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/formhandle.php">Projection Page</a></li>
    <li><a href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/division_query.php">Division Query</a></li>
    <li><a href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/join_query.php">Join Query</a></li>
    <li><a href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/login.php">Login Page</a></li>

  </ul>
</div>
</nav>

<div class="container" style="height: 100vh">

<div class="container-fluid">
<h2>Projection and Selection</h2>
</div>

<br>

<table border="0">

<h3> Projection </h3>
<form action="formhandle.php" method="post" autocomplete="off">
Table: <input type="text" name="table" required><br>   
Column: <input type="text" name="column1" required><br>
Column: <input type="text" name="column2" required><br>

<h3> Selection </h3>
Row: <input type="text" name="row"><br>
// eg. Char_ID=5, Char_Level>10, I_Value<=100
<br>
<br>

<td colspan="2" align="center">
<input type="submit" value="Perform Query" class="btn btn-primary" name="projection">
</form>
 
<br> 
<form methods="POST" action="formhandle.php">
<p><input type="submit" value="Reset" name="reset"></p> 
</form>
</table>

<p> <a href = "login.php"> <button type = "button"> Back </button> </a> </p>

</body>
</html> 

<?php
include("db_execute.php");
$success = True; //keep track of errors so it redirects the page only if there are no errors
$db_conn = OCILogon("ora_s4i0b", "a31112148", "dbhost.ugrad.cs.ubc.ca:1522/ug");


if ($db_conn) {

	if (array_key_exists("projection", $_POST)){
    $tuple = array (
        ":column1" => $_POST['column1'],
				":column2" => $_POST['column2'],
				":table" => $_POST['table'],
				":row" => $_POST['row']
            );
            $alltuples = array (
                $tuple
            ); 

				$result = executeBoundSQL("select (:column1, :column2) from (:table) where (:row)", $alltuples);
	

				if ($_POST && $success){
					header("location: formhandle.php");
        }

        echo "<br><h2>Query<h2><br>";
        echo "<table class='table table-bordered'>";
        $column1 = $_POST['column1'];
			  $column2 = $_POST['column2'];
        echo "<tr> <th>$column1</th> <th>$column2</th> </tr>";
        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
          echo "<tr>";
          echo "<td>" . $row['column1'] . "</td>";
          echo "<td>" . $row['column2'] . "</td>";
          echo "</tr>";
        }
        echo "</table>";
        OCICommit($db_conn);
    }
  }
?>

