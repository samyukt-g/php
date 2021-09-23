<?php
require_once "pdo.php";
session_start();

// DIE STATEMENT
if (! isset($_SESSION['name'])) {
	die("ACCESS DENIED");
}
if (isset($_POST['cancel'])) {
	header( 'Location: index.php' );
}

if (isset($_POST['fname']) && isset($_POST['lname']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])) {

	// DATA VALIDATION
	if (empty($_POST['fname']) || empty($_POST['lname']) || empty($_POST['email']) || empty($_POST['headline']) || empty($_POST['summary'])) {
		$_SESSION['error'] = 'All fields are required';
		header('Location: edit.php?profile_id=' . $_POST['profile_id']);
		return;
	}
	if (strpos($_POST['email'], '@') == false) {
		$_SESSION['error'] = 'Email address must contain @';
		header('Location: edit.php?profile_id=' . $_POST['profile_id']);
		return;
	}

	$sql = "UPDATE profile SET first_name = :fn, last_name = :ln, email = :em, headline = :hdl, summary = :smry WHERE profile_id = :id";
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array(
		':id' => $_POST['profile_id'],
		':fn' => $_POST['fname'],
		':ln' => $_POST['lname'],
		':em' => $_POST['email'],
		':hdl' => $_POST['headline'],
		':smry' => $_POST['summary']));
	$_SESSION['success'] = 'Profile edited';
	header( 'Location: index.php' );
	return;

}

// Guardian: Make sure that autos_id is present
if ( ! isset($_GET['profile_id']) ) {
	$_SESSION['error'] = "Missing profile_id";
	header('Location: index.php');
	return;
}

$stmt = $pdo->prepare("SELECT * FROM profile where profile_id = :xyz");
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
	<title>Samyukt G- Edit.php</title>
</head>
<body>
	<h1>Editing Profile for <?= htmlentities($_SESSION['name']) ?></h1>
	<?php
		// Flash pattern
		if ( isset($_SESSION['error']) ) {
		    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
		    unset($_SESSION['error']);
		}
		$id = htmlentities($row['profile_id']);
		$fn = htmlentities($row['first_name']);
		$ln = htmlentities($row['last_name']);
		$em = htmlentities($row['email']);
		$hdl = htmlentities($row['headline']);
		$smry = htmlentities($row['summary']);
	?>
	<form method="post">
		<div style="display: flex; padding: 0; margin: 0;">
			<p>First Name: <br><input value="<?= $fn ?>" type="text" name="fname" size="40"></p>
			<p>Last Name: <br><input value="<?= $ln ?>" type="text" name="lname" size="40"></p>
		</div>
		<p>Email: <br>
			<input value="<?= $em?>" type="text" name="email" size="40"></p>
		<p>
			Headline: <br>
			<input value="<?= $hdl ?>" type="text" name="headline" size="86">
		</p>
		<p>
			Summary: <br>
			<textarea name="summary" rows="8" cols="78"><?= $smry ?></textarea>
		</p>
		<input type="hidden" name="profile_id" value="<?= $id ?>">
		<p>
			<input type="submit" value="Save">
			<input type="submit" value="Cancel">
		</p>
	</form>
</body>
</html>