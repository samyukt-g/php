<?php
require_once 'pdo.php';
session_start();

// DIE STATEMENT
if (! isset($_SESSION['name'])) {
	die("ACCESS DENIED");
}
if (isset($_POST['cancel'])) {
	header( 'Location: index.php' );
	return;
}

// Data Validation
if (isset($_POST['fname']) && isset($_POST['lname']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])) {
	if (empty($_POST['fname']) || empty($_POST['lname']) || empty($_POST['email']) || empty($_POST['headline']) || empty($_POST['summary'])) {
		$_SESSION['error'] = 'All fields are required';
		header('Location: add.php');
		return;
	}
	if (strpos($_POST['email'], '@') == false) {
		$_SESSION['error'] = 'Email address must contain @';
		header('Location: add.php');
		return;
	}

	$sql = "INSERT INTO profile (user_id, first_name, last_name, email, headline, summary) values(:id, :fn, :ln, :em, :hdl, :smry)";
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array(
		':id' => $_SESSION['user_id'],
		':fn' => $_POST['fname'],
		':ln' => $_POST['lname'],
		':em' => $_POST['email'],
		':hdl' => $_POST['headline'],
		':smry' => $_POST['summary']));
	$_SESSION['success'] = 'Profile added';
	header('Location: index.php');
	return;
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Samyukt G- Add.php</title>
</head>
<body>
	<h1>Adding Profile for <?= $_SESSION['name'] ?></h1>
	<?php  
		if ( isset($_SESSION['error']) ) {
		  echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
		  unset($_SESSION['error']);
		}
	?>
	<form method="POST">
		<p>First Name: <br><input type="text" name="fname" size="40"></p>
		<p>Last Name: <br><input type="text" name="lname" size="40"></p>
		<p>Email: <br><input type="text" name="email" size="40"></p>
		<p>
			Headline: <br>
			<input type="text" name="headline" size="86">
		</p>
		<p>
			Summary: <br>
			<textarea name="summary" rows="8" cols="78"></textarea>
		</p>
		<p>
			<input type="submit" value="Add">
			<input type="submit" name="cancel" value="Cancel">
		</p>
	</form>
</body>
</html>