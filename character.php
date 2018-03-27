<?php
    ini_set('session.save_path', './');
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body background="pix/bg1.jpg">

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/user.php">Character Page</a>
    </div>
    <ul class="nav navbar-nav">
      <li><a href="http://www.ugrad.cs.ubc.ca/~s4i0b/SquishyBoots/character.php">Character Details</a></li>
    </ul>
  </div>
</nav>

<div class="container-fluid">
  <h2>Aggregation Screen</h2>
</div>


<p>Find stats about your items</p>
<form action="character.php" method="GET" id="MaxAggregationForm">
<input type="submit" value="Most Expensive" class="btn btn-primary" name="maxAggregation">
</form>

<form action="character.php" method="GET" id="MinAggregationForm">
<input type="submit" value="Least Expensive" class="btn btn-primary" name="minAggregation">
</form>

<form action="character.php" method="GET" id="SumAggregationForm">
<input type="submit" value="Total Value" class="btn btn-primary" name="sumAggregation">
</form>

<?php
include("db_execute.php");
$success = True; //keep track of errors so it redirects the page only if there are no errors
$db_conn = OCILogon("ora_s4i0b", "a31112148", "dbhost.ugrad.cs.ubc.ca:1522/ug");
if ($db_conn) {
    echo "<script>console.log( 'DB Connected' );</script>";
    $player_id = $_SESSION['Player_ID'];
    $char_id;
    if(isset($_GET['Char_id']) && !empty($_GET['Char_id'])){
        $char_id = $_GET['Char_id'];
        $_SESSION['Char_ID'] = $char_id;
        session_write_close();
    }
    else{
        $char_id = $_SESSION['Char_ID'];
    }
    //Debugging
    echo "<script>console.log( 'Player_ID' + $player_id );</script>";
    echo "<script>console.log( 'Char_ID' + $char_id );</script>";
    if(array_key_exists('maxAggregation', $_GET)){
        echo "<script>console.log( 'Max Button Pressed' );</script>";
        $_SESSION["Agg_Query"] = "SELECT MAX(I.I_Value) FROM Item I, Carries C, Hero H, Player P WHERE I.Item_ID = C.Item_ID AND C.Char_ID = H.Char_ID AND H.Player_ID = P.Player_ID AND P.Player_ID = $player_id AND H.Char_ID = $char_id";
    session_write_close();
    header("location: character.php");
    } elseif(array_key_exists('minAggregation', $_GET)){
    echo "<script>console.log( 'Min Button Pressed' );</script>";
    $_SESSION["Agg_Query"] = "SELECT MIN(I.I_Value) FROM Item I, Carries C, Hero H, Player P WHERE I.Item_ID = C.Item_ID AND C.Char_ID = H.Char_ID AND H.Player_ID = P.Player_ID AND P.Player_ID = $player_id AND H.Char_ID = $char_id";
    session_write_close();
    header("location: character.php");
    } elseif(array_key_exists('sumAggregation', $_GET)){
        echo "<script>console.log( 'Sum Button Pressed' );</script>";
        $_SESSION["Agg_Query"] = "SELECT SUM(I.I_Value) FROM Item I, Carries C, Hero H, Player P WHERE I.Item_ID = C.Item_ID AND C.Char_ID = H.Char_ID AND H.Player_ID = P.Player_ID AND P.Player_ID = $player_id AND H.Char_ID = $char_id";
        
        session_write_close();
        header("location: character.php");
    } else {
        if(isset($_SESSION["Agg_Query"])){
            $query = $_SESSION['Agg_Query'];
            $result = executePlainSQL($query);
            OCICommit($db_conn);
            echo "<br><h4>Result from Aggregation Query</h4><br>";
            echo "<table class='table table-bordered'>";
            echo "<tr><th>Total Value</th></tr>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td></tr>";
                //echo $row[0];
            }
            echo "</table><br>";
            unset($_SESSION['Agg_Query']);
        }
        $characterResult = executePlainSQL("SELECT C.Char_ID, C.Char_Name, C.Char_Level, C.HP, C.MP, H.Hero_Class, H.Job, H.Quests_Completed FROM Characters C, Hero H, Player P WHERE C.Char_ID = H.Char_ID AND H.Player_ID = P.Player_ID AND P.Player_ID = $player_id AND C.Char_ID = $char_id");
        OCICommit($db_conn);
        echo "<br><h2>Your Character<h2><br>";
        echo "<table class='table table-bordered'>";
        echo "<tr> <th>Char ID</th> <th>Name</th> <th>Level</th> <th>HP</th> <th>MP</th> <th>Class</th> <th>Job</th> <th>Quests Completed</th> <th>Quests</th></tr>";
        while ($row = OCI_Fetch_Array($characterResult, OCI_BOTH)) {
            echo "<tr>";
            echo "<td>" . $row['CHAR_ID'] . "</td>";
            echo "<td>" . $row['CHAR_NAME'] . "</td>";
            echo "<td>" . $row['CHAR_LEVEL'] . "</td>";
            echo "<td>" . $row['HP'] . "</td>";
            echo "<td>" . $row['MP'] . "</td>";
            echo "<td>" . $row['HERO_CLASS'] . "</td>";
            echo "<td>" . $row['JOB'] . "</td>";
            echo "<td>" . $row['QUESTS_COMPLETED'] . "</td>";
            echo "<td> <a href='quest_list.php'> Quest Details</a></td>";
            echo "</tr>";
        }
        echo "</table>";
        $itemsResult = executePlainSQL("SELECT I.Item_ID, I.I_Name, I.I_Type, I.I_Level, I.I_Value FROM Item I, Carries C, Hero H, Player P WHERE I.Item_ID = C.Item_ID AND C.Char_ID = H.Char_ID AND H.Player_ID = P.Player_ID AND P.Player_ID = $player_id AND H.Char_ID = $char_id");
        OCICommit($db_conn);
        echo "<br><h2>Your Items<h2><br>";
        echo "<table class='table table-bordered'>";
        echo "<tr> <th>Item ID</th> <th>Name</th> <th>Type</th> <th>Level</th> <th>Value</th></tr>";
        while ($row = OCI_Fetch_Array($itemsResult, OCI_BOTH)) {
            echo "<tr>";
            echo "<td>" . $row['ITEM_ID'] . "</td>";
            echo "<td>" . $row['I_NAME'] . "</td>";
            echo "<td>" . $row['I_TYPE'] . "</td>";
            echo "<td>" . $row['I_LEVEL'] . "</td>";
            echo "<td>" . $row['I_VALUE'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
}
?>

<p>Delete an item by giving the item_id</p>
<form action="character.php" method="POST" id="delete_item" autocomplete="off">
<input type="text" class="form-control" name="item" width="20">
<input type="submit" value="Delete Item" class="btn btn-primary" name="delete_item">
</form>

<?php
    if ($db_conn) {
        if(array_key_exists("delete_item", $_POST)){
            $_SESSION["DEBUG"] = 1;
            $tuple = array (
                ":bind1" => $char_id,
                ":bind2" => $_POST['item']
            );
            $alltuples = array (
                $tuple
            );
            executeBoundSQL("delete from Carries where Char_id = :bind1 and Item_id = :bind2", $alltuples);
            OCICommit($db_conn);
            header("location: character.php");
        }
    }
?>
        
</body>

</html>