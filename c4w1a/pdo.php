<?php 
	$pdo = new PDO('mysql:hosst=localhost;port=3306;dbname=misc', 'fred', 'zap');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);