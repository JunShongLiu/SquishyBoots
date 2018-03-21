<?php
	ini_set('session.save_path', './');
	session_start();
	echo session_id();
	print_r ($_SESSION);
	echo "Favorite color is " . $_SESSION['player_id'] . ".<br>";
?>

<html>
<p>If PHP is working, you will see "Hello World" below:<hr>
<?php
   echo "Hello world foo";
   phpinfo();  // Print PHP version and config info
?>

</html>