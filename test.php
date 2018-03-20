<html>
<?php

if ($c=OCILogon("ora_s4i0b", "a31112148", "dbhost.ugrad.cs.ubc.ca:1522/ug")) {
  echo "Successfully connected to Oracle.\n";

$query = "SELECT * FROM Character"; 
$result = oci_parse($db_conn, $query); 
oci_execute($result);

$class = isset($_POST['class']) == true ? $_POST['class'] : '';
$job = isset($_POST['job']) == true ? $_POST['job'] : '';
$charname = isset($_POST['charname']) == true ? $_POST['charname'] : '';

echo $class;
echo $job;
echo $charname;

while (($row = oci_fetch_array($result, OCI_ASSOC)) != false){
    echo $row[0].\n; 

}

  OCILogoff($c);



} else {
  $err = OCIError();
  echo "Oracle Connect Error " . $err['message'];
}




?>
</html>