<?php
require_once "pdo.php";
if ( !isset($_GET['name']) || strlen($_GET['name']) < 1) {
    die("Name parameter missing");
}
if ( isset($_POST['logout']) ) {
  header('Location: index.php');
}
$name = $_GET['name'];
$failureMsg = false;
$successMsg = false;

// PDO::PREPARE
$stmt = $pdo->prepare("INSERT INTO Autos (make, year, mileage) VALUES (:mk, :yr, :mlg)");

// FORM::VALIDATION
if (empty($_POST['make'])) {
  $failureMsg = 'Make is required';
} else {
  $make = $_POST['make'];
  if (is_numeric($_POST['mileage']) && is_numeric($_POST['year'])){
    $mile = $_POST['mileage'];
    $year = $_POST['year'];
    // PDO::EXECUTE
    $stmt->execute(array(
        ':mk' => $make,
        ':yr' => $year,
        ':mlg' => $mile
    ));
    $failureMsg = false;
    $successMsg = 'Record inserted';
  } else {
    $failureMsg = 'Mileage and year must be numeric';
  }
}
 ?>
 <!------------------------------------------------------------------------->
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Samyukt G</title>
    <link rel="stylesheet" href="autos.css">
    <link rel="stylesheet" href="css/autos.css">
  </head>
  <body>
    <main class="main-container">
      <h1>
        <?php print "Tracking Autos for " . $name; ?>
      </h1>
      <?php
        if (isset($_POST['add'])) {
          if ( $failureMsg !== false ) {
            echo '<p style="color: red;">' . htmlentities($failureMsg) . '</p>';
          } elseif ($successMsg !== false) {
            echo '<p style="color: green;">' . htmlentities($successMsg) . '</p>';
          }
        }
       ?>
      <form method="post">
        <label for="make">Make:&nbsp&nbsp&nbsp&nbsp</label>
        <input type="text" name="make">
        <br>
        <label for="year">Year:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp </label>
        <input type="text" name="year">
        <br>
        <label for="mileage">Mileage: </label>
        <input type="text" name="mileage">
        <br>
        <input type="submit" name="add" value="Add">
        <input type="submit" name="logout" value="Logout">
      </form>
      <br>
      <h2>Automobiles</h2>
      <ul>
        <?php
          $SELECTALL = $pdo->query("SELECT * FROM Autos");
          while ($row = $SELECTALL->fetch(PDO::FETCH_ASSOC)) {
            echo '<li>' . htmlentities($row['year']) . ' ' . htmlentities($row['make']) . ' / ' . htmlentities($row['mileage']) . '</li>';
          }
         ?>
      </ul>
    </main>
  </body>
</html>
