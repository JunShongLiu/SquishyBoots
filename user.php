<html>
    <head>
        <title>PHP Test</title>
    </head>
    <body>
        <?php echo '<p>Hello Existing User</p>'; ?>
	<div id = 'user">
	    <p id = 'login_existing"> Hello Existing User : 
		<i> <?php echo $_SESSION['username']; ?> </i> </p>
	    <p> <a href = "login.php"> <button type = "button"> Logout </button> </a> </p>
	</div>
    </body>
</html>
