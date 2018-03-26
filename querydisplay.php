
<DOCTYPE html>
<html>
	<head>
		<title>
			Selection & Projection
		</title>
	</head>



<?php

$db_conn = OCILogon("ora_y0w0b", "a21529145", "dbhost.ugrad.cs.ubc.ca:1522/ug");
if (!$db_conn){
    $err = OCIError();
    echo "Connection failed" . $err['message'];
}

// $charID = isset($_POST['class']) == true ? $_POST['class'] : '';
// $job = isset($_POST['job']) == true ? $_POST['job'] : '';
// $charname = isset($_POST['charname']) == true ? $_POST['charname'] : '';

// $query = "SELECT * FROM Character"; 
// $result = oci_parse($db_conn, $query); 
// oci_execute($result);

// while (($row = oci_fetch_array($result, OCI_ASSOC)) != false){
//     echo $row['charID']; 

// }

// echo "<table border='1'>\n";
// while ($row = oci_fetch_array($result, OCI_ASSOC + OCI_RETURNS_NULL)){
//     echo "<tr>\n";
//     foreach($row as $item){
//         echo "<td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "nbsp;") . " 
//         </td>\n"; 
//     }
//     echo "</tr>\n";

//     }
//     echo "</table>\n";
// }
OCILogoff($db_conn);

?>
</html> 


