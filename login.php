<DOCTYPE html>
<html>
	<head>
		<title>
			PHP Login Form using Sessions
		</title>
	</head>
<body>
<div id="main">
	<div id="user_login">
		<h2> Login </h2>
			<form action="" method="post">
				<label> Username: </label> <br>
					<input id = "username" name = "username" placeholder = "username" type="text">
				<label> Password: </label> <br>
					<input id = "password" name = "password" placeholder = "password" type="text">
				<input name= "enter" type = "submit" value = "Login">
					<a href="/~v0i0b/SquishyBoots/register.php">
						<button type = "button"> Register </button>
					</a>
				<span> <?php echo $error; ?> </span>
			</form>
	</div>
</div>

</body>
</html>

