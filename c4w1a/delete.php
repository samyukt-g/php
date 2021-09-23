<?php 
require_once "pdo.php";
session_start();

// DIE STATEMENT
if (! isset($_SESSION['name'])) {
    die("ACCESS DENIED");
}
if (isset($_POST['cancel'])) {
    header( 'Location: index.php' );
    return;
}

// DATA VALIDATION
if ( isset($_POST['delete']) && isset($_POST['profile_id']) ) {
    $sql = "DELETE FROM profile WHERE profile_id = :zip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':zip' => $_POST['profile_id']));
    $_SESSION['success'] = 'Profile deleted';
    header( 'Location: index.php' ) ;
    return;
}

// Guardian: Make sure that user_id is present
if ( ! isset($_GET['profile_id']) ) {
  $_SESSION['error'] = "Missing profile_id";
  header('Location: index.php');
  return;
}

$stmt = $pdo->prepare("SELECT first_name, last_name, profile_id FROM profile where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for profile_id';
    header( 'Location: index.php' ) ;
    return;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Samyukt G- Delete.php</title>
</head>
<body>
    <h1>Deleteing Profile</h1>
    <p>First Name: <?= htmlentities($row['first_name'])?></p>
    <p>Last Name: <?= htmlentities($row['last_name'])?></p>

    <form method="post">
        <input type="hidden" name="profile_id" value="<?= $row['profile_id'] ?>">
        <input type="submit" value="Delete" name="delete">
        <input type="submit" name="cancel" value="Cancel">
    </form>
</body>
</html>