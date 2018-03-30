<html>
<head>
 <title>New Account</title>
 <html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
</head>

<body background="pix/bg1.jpg">


<form action="login.php" method="post">
<div class="text-center">
 <h1>Create New Account</h1><br/>

 <span class="input"></span>
 <input type="text" name="username" placeholder="Username" title="username" autocomplete="off" required />
 <br>
 <span class="input"></span> 
 <input type="email" name="email" placeholder="Email address" required />
 <br>
 <button type="submit" value="Sign Up" title="Submit form" class="btn btn-primary" name="registerform"><span>Register</span></button>

</form>

<br>
<br>

<form action = "login.php">
<button type="submit" class="btn btn-primary"> Back to Login</button>
</div>
</form>


</body>
</html>

<?php
include("db_execute.php");

$success = True; //keep track of errors so it redirects the page only if there are no errors
$db_conn = oci_connect("ora_s4i0b", "a31112148", "dbhost.ugrad.cs.ubc.ca:1522/ug");

if ($db_conn){

   if (array_key_exists("registerform", $_POST)){
    
       $tuple = array (
           ":bind1" => $_POST['username'],
           ":bind2" => $_POST['email'],
           ":bind3" => rand(15, 1000)
       );

       $alltuples = array (
           $tuple
       ); 
           
       executeBoundSQL("insert into Player values (:bind1, :bind2, :bind3)", $alltuples);
       echo "account successfully created!"; 
       OCICommit($db_conn);
       header("location: register.php");     
   }
}
?>