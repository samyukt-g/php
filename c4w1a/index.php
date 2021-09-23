<?php
session_start();
require_once 'pdo.php';

// isEmpty()
$existsQuery = $pdo->query("SELECT EXISTS(SELECT 1 FROM profile) AS is_empty;");
$existsFetch = $existsQuery->fetch(PDO::FETCH_ASSOC);
$profileEmpty = true;
if ($existsFetch['is_empty'] == 1) { 
	$profileEmpty = false;
} 

// Select
$stmt = $pdo->query("SELECT profile_id, first_name, last_name, headline FROM profile")
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Samyukt G- Index.php</title>
</head>
<body>
	<h1>Samyukt's Resume Registry</h1>
	<!-- Flash messages -->
	<?php
		if (isset($_SESSION['success'])) {
			echo '<p style="color: green">' . $_SESSION['success'] . '</p>';
			unset($_SESSION['success']);
		}
		if (isset($_SESSION['error'])) {
			echo '<p style="color: red">' . $_SESSION['error'] . '</p>';
			unset($_SESSION['error']);
		}
	?>
	<?php
		if ( ! isset($_SESSION['name']) ) {
			echo '</p><a href="login.php">Please log in</a></p>';
		} else {
			if ($profileEmpty === true) {
				echo '<p><a href="logout.php">Logout</a></p>';
				echo '<p><a href="add.php">Add New Entry</a></p>';
			} else {
				echo '<p><a href="logout.php">Logout</a></p>';
				echo '<table border="2">
						<thead>
							<tr>
								<th>Name</th>
								<th>Headline</th>
								<th>Action</th>
							</tr>
						</thead><tbody>';
				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
					echo '<tr><td>';
					echo (htmlentities($row['first_name'] . " " . $row['last_name']));
					echo '</td><td>';
					echo (htmlentities($row['headline']));
					echo '</td><td>';
				    echo('<a href="edit.php?profile_id='.$row['profile_id'].'">Edit</a> / ');
				    echo('<a href="delete.php?profile_id='.$row['profile_id'].'">Delete</a>');
				    echo("</td></tr>\n");

				}
				echo '</tbody></table>';
				echo '<p><a href="add.php">Add New Entry</a></p>';	
			}
		}
	?>
</body>
</html>