<?php
echo "<a href='index.php'><h4>Home</h4></a>";
include 'dbConn.php';
$conn = dbConn();

function getGameByID() {
  global $conn;
  $sql = "SELECT * FROM `games` a left join `recommendation` b ON a.gameID = b.gameID WHERE a.gameID = :gameID ";

  $namedParameters = array();
  $namedParameters[':gameID'] = $_GET['gameID'];
  $statement = $conn->prepare($sql);    
  $statement->execute($namedParameters);
  
  while ($data = $statement->fetch()) {
    $record[] = $data;
  }
  echo $data; //TESTING
  return $record;
 }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <title>Pokemon Recommendations</title>
  <link href="bootstrap.min.css" rel="stylesheet" type="text/css" />
</head>

<body><center>
  <?php $game = getGameByID()?>
  <header>
    <h1><?=$game['title']?> Pokemon Game Recommendations</h1>
    <br />
  </header>
    
  <?php 
    if (isset($_GET['addRecommendation'])) {

      $sql = "INSERT INTO recommendation ( gameID, name, date, comment) 
              VALUES ( :gameID, :name, :date, :comment)";
      $namedParameters = array();
      $namedParameters[':gameID'] = $_GET['gameID'];
      $namedParameters[':name'] = $_GET['name'];
      $namedParameters[':date'] = $_GET['date'];
      $namedParameters[':comment'] = $_GET['comment'];

      $statement = $conn->prepare($sql);
      $statement->execute($namedParameters);  
      echo "Recommendation added!<br />";
    }
      
  ?>
  
  <div>
    <?php
    $exists = false;
    echo "<table border=1>";
    echo "<th>Reader</th>";		
    echo "<th>Date</th>";
    echo "<th>Comment</th>";
    foreach ($game as $games) {
      $exists = true;
      echo "<tr>"; 
      echo "<td>" . $games['name'] . "</td>";
      echo "<td>" . $games['date'] . "</td>";
      echo "<td>" . $games['comment'] . "</td>";
      echo "</tr>";
    }
  
    if(!$exists){
      echo "<tr><td colspan='3'>No recommendations for this game.</td></tr>";
    }
    echo "</table>";
    ?>
    
    </center>
    <br /><br />
    <form>
      Reader: <input type="text" name="name"><br />
      Date: <input type="date" name="date"><br />
      Comment: <textarea rows="4" cols="20" name="comment" /></textarea> <br />
      <input type="hidden" name="gameID" value="<?=$_GET['gameID']?>" />
      <br />
      <input type="submit" value="Add Recommendation" name="addRecommendation" />
    </form>
    </div>
  </div>
</body>
</html>