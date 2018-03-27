<html>
 <head>
  <title>New Account</title>
 </head>
 <body background="pix/bg1.jpg">
 


<form action="register.php" method="post">
  <h1>Create New Account</h1><br/>

  <span class="input"></span>
  <input type="text" name="username" placeholder="Username" title="wassup:" autofocus autocomplete="off" required />
  <br>
  <span class="input"></span> 
  <input type="email" name="email" placeholder="Email address" required />
  <br>
  <span id="passwordMeter"></span>
  <input type="text" name="playerid" id="playerid" placeholder="Player ID" required />
  <button type="submit" value="Sign Up" title="Submit form" class="icon-arrow-right" name="registerform"><span>Register</span></button>
</form>

<br>
<br>

<form action = "login.php">
<button type="submit" class="btn btn-primary">Back to Login</button>
</form>


 </body>
</html>

<?php
include("db_execute.php");

$success = True; //keep track of errors so it redirects the page only if there are no errors
$db_conn = OCILogon("ora_s4i0b", "a31112148", "dbhost.ugrad.cs.ubc.ca:1522/ug");

if ($db_conn) {
	echo "<script>console.log( 'DB Connected' );</script>";
	$player_id = $_SESSION['Player_ID'];

	echo "<script>console.log( 'Player_ID' + $player_id );</script>";

	OCICommit($db_conn);

    	if (array_key_exists("registerform", $_POST)){


            $tuple = array (
                ":username" => $_POST['username'],
				":email" => $_POST['email'],
				":playerID" => $_POST['playerid']
            );
            $alltuples = array (
                $tuple
            ); 
				executeBoundSQL("insert into Player values(:username, :email, :playerID)", $alltuples);
		        OCICommit($db_conn);

				if ($_POST && $success){
					header("location: register.php");
				}
		}
}

?>