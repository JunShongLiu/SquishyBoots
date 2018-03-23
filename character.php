<html>
      <head>
          <title>PHP Test</title>
      </head>
      <body>
          <div id = 'user">
              <p id = 'login_existing"> Hello Existing Character :
                  <i> <?php echo $_SESSION['username']; ?> </i> </p>
              <p> Find details about this character:  </p>
              <form action="character.php" method="GET" id="AggregationForm">
              <input type="submit" value="Execute Query"  name="aggregation">
              </form>
              <p> <a href = "user.php"> <button type = "button"> Back </button> </a> </p>
          </div>
      </body>
  </html>
