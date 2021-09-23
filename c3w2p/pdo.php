<?php

$username = 'serveradmin';
$password = 'qwerty';

$pdo = new PDO('mysql:host:localhost;port=3306;dbname=misc', $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
