<DOCTYPE html>
<html>
	<head>
		<title>
			Selection & Projection
		</title>
	</head>
<body>

<h2> Projection </h2>
<form action="querydisplay.php" method="get">
Table: <input type="text" name="table"><br>   
Column: <input type="text" name="column1"><br>
Column: <input type="text" name="column2"><br>

<h2> Selection </h2>

Row: <input type="text" name="row1"><br>
Row: <input type="text" name="row2"><br>
<form methods="POST" action="formhandle.php">
<input type="submit">

</form>

<br> 
<form methods="POST" action="formhandle.php">
<p><input type="submit" value="Reset" name="reset"></p> </form>


</body>
</html> 


