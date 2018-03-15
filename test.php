<html>
<?php

if ($c=OCILogon("ora_s4i0b", "a31112148", "dbhost.ugrad.cs.ubc.ca:1522/ug")) {
  echo "Successfully connected to Oracle.\n";
  OCILogoff($c);
} else {
  $err = OCIError();
  echo "Oracle Connect Error " . $err['message'];
}

?>
</html>