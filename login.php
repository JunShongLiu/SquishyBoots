<DOCTYPE html>
<html>
    <head>
	<title>
	    PHP Login Form using Session
	</title>
    </head>
<body>
<div id="main">
    <div id="user_login">
	<h2> Login </h2>
	    <form action="login.php" method="post">
	        <label> Username: </label> <br>
		    <input id = "username" name = "username" placeholder = "username" type="text">
		<label> Password: </label> <br>
		    <input id = "password" name = "password" placeholder = "password" type="text">
		<input name= "login" type = "submit" value = "Login">
		    <a href="/~v0i0b/SquishyBoots/register.php">
			<button type = "button"> Register </button>
		    </a>
		<span> <?php echo $error; ?> </span>
	    </form>
    </div>
</div>
</body>
</html>

<?php
    include("db_execute.php");
    $success = True;
    $db_conn = OCILogon("ora_s4i0b", "a31112148", "dbhost.ugrad.cs.ubc.ca:1552/ug");
    if ($db_conn) {
	echo "can connect";
	if (array_key_exists("login", $_POST)) {
	    $username = $_POST['username'];
	    $select_query = "SELECT Player_ID FROM Player WHERE Username = '$username'";
	    $player_id = executePlainSQL(select_stmt);
	    $_SESSION['player_id'] = $player_id;
	    OCICommit($db_conn);
	}
	if ($_POST && $success) {
	    header('Location: user.php');
	}
	OCILogoff($db_conn);
    } else {
	echo "cannot connect";
	$err = OCI_Error();
	echo htmlentities($e['message']);
    }

?>
