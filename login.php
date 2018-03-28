<!-- 

This is the login screen

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
<body background="pix/bg1.jpg">


	<div class="page-header text-center">
		<h1>SquishyBoots</h1>
		<img src='pix/logo.jpg'>
		<!--<p> We need a logo! </p>-->
	</div>


	<br>
	<br>
	<br>

	<div class="container" style="height: 100vh">
        <div class="text-center">
                        <form action="login.php" method="GET" autocomplete="off">
                        	Email
                            <div class="form-group">
                                <input type="text" class="form-control" name="email" width="20">
                            </div>
                            <input type="submit" id="sendlogin" value="Login" name="login" class="btn btn-primary">
                        </form>
						<form action = "register.php">
    			<button type="submit" class="btn btn-primary">Register</button>
    			</form>
        </div>
        <br>
        <br>
        <div class="text-center">
    			<form action = "delete.php">
    			<button type="submit" class="btn btn-primary">Admin Page</button>
    			</form>
				
    	</div>
    </div>


</body>

</html>

<?php
	include("db_execute.php");
	$success = True; //keep track of errors so it redirects the page only if there are no errors
	$db_conn = OCILogon("ora_s4i0b", "a31112148", "dbhost.ugrad.cs.ubc.ca:1522/ug");
	if($db_conn){

		echo "<script>console.log( 'DB Connected' );</script>";
		if(array_key_exists("login", $_GET)){
			$email = $_GET['email'];
			$query = "select Player_ID from Player where Email = '$email'";

			$result = executePlainSQL($query);

			$_SESSION['result'] = $result;

			$i = 0;
			while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
				$i++;
				$_SESSION['Player_ID'] = $row[0];
			}
			if($i == 0){
				echo "<script type='text/javascript'>alert('User Does Not Exist!!!');</script>";
			}
			else {
				oci_free_statement($result);
				session_write_close();
				header('Location: user.php');
			}
		}
	}
?>