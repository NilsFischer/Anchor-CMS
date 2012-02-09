<?php

/*
	Database connection test
*/

$fields = array('host', 'user', 'pass', 'db');
$post = array();

foreach($fields as $field) {
	$post[$field] = isset($_POST[$field]) ? $_POST[$field] : false;
}

if(empty($post['db'])) {
	$errors[] = 'Bitte ändere den Namen der Datenbank.';
}

if(empty($post['host'])) {
	$errors[] = 'Bitte trage einen anderen, Host für die Datenbank ein.';
}

// test database
if(empty($errors)) {
	try {
		$dsn = 'mysql:dbname=' . $post['db'] . ';host=' . $post['host'];
		new PDO($dsn, $post['user'], $post['pass']);
	} catch(PDOException $e) {
		$errors[] = $e->getMessage();
	}
}

// output response
header('Content-Type: text/plain');

if(empty($errors)) {
	//no errors we're all gooood
	echo 'Gut';
} else {
	echo implode(', ', $errors);
}
