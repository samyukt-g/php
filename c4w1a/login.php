<?php
session_start();
require_once "pdo.php";
unset($_SESSION['name']);
unset($_SESSION['user_id']);

if (isset($_POST['cancel'])) {
	header('Location: index.php');
}

$salt = 'XyZzy12*_';

// Form validation
if (isset($_POST['email']) && isset($_POST['pass'])) {
	$email = $_POST['email'];
	$pswrd = $_POST['pass'];
	if (empty($email) || empty($pswrd)) {
		$_SESSION['error'] = 'Email and password are required';
		header('Location: login.php');
		return;
	} 
	$check = hash('md5', $salt . $pswrd);
	$sql = 'SELECT user_id, name FROM users WHERE email = :em AND password = :pw';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array(
		':em' => $email,
		':pw' => $check));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	if ($row === false) {
		$_SESSION['error'] = 'Incorrect password';
		header('Location: login.php');
		return;
	} else {
		$_SESSION['name'] = $row['name'];
		$_SESSION['user_id'] = $row['user_id'];
		unset($_SESSION['error']);
		header('Location: index.php');
		return;
	}

}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Samyukt G- Login.php</title>
</head>
<body>
	<h1>Please Log In</h1>
	<!-- Flash messages -->
	<?php 
		if (isset($_SESSION['error'])) {
			echo '<p style="color: red">' . $_SESSION['error'] . '</p>';
			unset($_SESSION['error']);
		}
	?>
	<form method="POST" action="login.php">
		<label for="email">Email</label>
			<input type="text" name="email" id="email"><br/>
		
		<label for="pass">Password</label>
			<input type="password" name="pass" id="pass">

		<br/>
			<input type="submit" onclick="return doValidate();" value="Log In">
			<input type="submit" name="cancel" value="Cancel">
	</form>

	<script>
		const doValidate = ()=> {
		    console.log('Validating...');
		    try {
		        addr = document.getElementById('email').value;
		        pw = document.getElementById('pass').value;
		        console.log("Validating addr="+addr+" pw="+pw);
		        if (addr == null || addr == "" || pw == null || pw == "") {
		            alert("Both fields must be filled out");
		            return false;
		        }
		        if ( addr.indexOf('@') == -1 ) {
		            alert("Invalid email address");
		            return false;
		        }
		        return true;
		    } catch(e) {
		        return false;
		    }
		    return false;
		}
	</script>
</body>
</html>