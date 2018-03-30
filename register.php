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


<form action="register.php" method="post">
<div class="container" style="width: 50vh">
<div class="text-center">
 <h1>Create New Account</h1><br/>

 <span class="input"></span>
 <input type="text" class="form-control" name="username" placeholder="Username" title="username" autocomplete="off" required />
 <br>
 <span class="input"></span> 
 <input type="email" class="form-control" name="email" placeholder="Email address" required />
 <br>
 <button type="submit" value="Sign Up" title="Submit form" class="btn btn-primary" name="registerform"><span>Register</span></button>

</form>

<br>
<br>

<form action = "login.php">
<button type="submit" class="btn btn-primary"> Back to Login</button>
</div>
</form> </div>

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

       $cmdstr = "insert into Player values (:bind1, :bind2, :bind3)";
       $statement = OCIParse($db_conn, $cmdstr);
       
           if (!$statement) {
               echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
               $e = OCI_Error($db_conn);
               echo htmlentities($e['message']);
               $success = False;
           }
       
           foreach ($alltuples as $tuple) {
               foreach ($tuple as $bind => $val) {
                   //echo $val;
                   //echo "<br>".$bind."<br>";
                   OCIBindByName($statement, $bind, $val);
                   unset ($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
       
               }
               $r = OCIExecute($statement, OCI_DEFAULT);
               OCICommit($db_conn);               
               if (!$r) {
                   echo "Account Registry Unsuccessful.";                
                   echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                   $e = OCI_Error($statement); // For OCIExecute errors pass the statementhandle
                   echo htmlentities($e['message']);
                   echo "<br>";
                   $success = False;
               }
               else{
                    header("location: login.php");                
               }
           }
           
   }
}
?>