<?php

/*
	Helper functions
*/
function random($length = 16) {
	$pool = str_split('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', 1);
	$value = '';

	for ($i = 0; $i < $length; $i++)  {
		$value .= $pool[mt_rand(0, 61)];
	}

	return $value;
}
	
/*
	Installer
*/

$fields = array('host', 'user', 'pass', 'db', 'name', 'description', 'theme', 'email', 'path', 'clean_urls');
$post = array();

foreach($fields as $field) {
	$post[$field] = isset($_POST[$field]) ? $_POST[$field] : false;
}

if(empty($post['db'])) {
	$errors[] = 'Bitte gib einen Datenbanknamen an';
}

if(empty($post['host'])) {
	$errors[] = 'Bitte gib einen Datenbankhost an';
}

if(empty($post['name'])) {
	$errors[] = 'Bitte gib einen Seitennamen ein';
}

if(empty($post['theme'])) {
	$errors[] = 'Bitte wähle ein Theme aus';
}

if(filter_var($post['email'], FILTER_VALIDATE_EMAIL) === false) {
	$errors[] = 'Bitte gib eine valide Email-Adresse ein';
}

if(version_compare(PHP_VERSION, '5.3.0', '<')) {
	$errors[] = 'Anchor benötigt PHP 5.3 oder neuer, bei dir läuft aktuell PHP ' . PHP_VERSION;
}

// test database
if(empty($errors)) {
	try {
		$dsn = 'mysql:dbname=' . $post['db'] . ';host=' . $post['host'];
		$dbh = new PDO($dsn, $post['user'], $post['pass']);
	} catch(PDOException $e) {
		$errors[] = $e->getMessage();
	}
}

// create config file
if(empty($errors)) {
	$template = file_get_contents('../config.default.php');
	
	$base_url = ($path = trim($post['path'], '/')) == '' ? '' : $path . '/';
	$index_page = ($post['clean_urls'] === false ? 'index.php' : '');

	$search = array(
		"'Datenbankhost' => 'localhost'",
		"'Benutzer' => 'root'",
		"'Passwort' => ''",
		"'Datenbankname' => 'anchorcms'",
		
		// apllication paths
		"'base_url' => '/'",
		"'index_page' => 'index.php'"
	);
	$replace = array(
		"'Datenbankhost' => '" . $post['host'] . "'",
		"'Benutzer' => '" . $post['user'] . "'",
		"'Passwort' => '" . $post['pass'] . "'",
		"'Datenbankname' => '" . $post['db'] . "'",

		// apllication paths
		"'base_url' => '/" . $base_url . "'",
		"'index_page' => '" . $index_page . "'"
	);
	$config = str_replace($search, $replace, $template);

	if(file_put_contents('../config.php', $config) === false) {
		$errors[] = 'Konnte keine Konfigurationsdatei erstellen.';
	}
	
	// if we have clean urls enabled let setup a 
	// basic htaccess file is there isnt one
	if($post['clean_urls']) {
		$htaccess = file_get_contents('../htaccess.txt');	
		$htaccess = str_replace('# RewriteBase /', 'RewriteBase /' . $base_url, $htaccess);
	
		if(file_put_contents('../.htaccess', $htaccess) === false) {
			$errors[] = 'Konnte keine .htaccess-Datei erstellen. Tu das um saubere URLs zu erlauben.';
		}
	}
}

// create db
if(empty($errors)) {
	// create a unique password for our installation
	$password = random(8);

	$sql = str_replace('[[now]]', time(), file_get_contents('anchor.sql'));
	$sql = str_replace('[[password]]', crypt($password), $sql);
	$sql = str_replace('[[email]]', strtolower(trim($post['email'])), $sql);
	
	try {
		$dbh->beginTransaction();
		$dbh->exec($sql);
		
		$sql= "INSERT INTO `meta` (`key`, `value`) VALUES ('sitename', ?), ('description', ?), ('theme', ?);";
		$statement = $dbh->prepare($sql);
		$statement->execute(array($post['name'], $post['description'], $post['theme']));

		$dbh->commit();
	} catch(PDOException $e) {
		$errors[] = $e->getMessage();
		
		// rollback any changes
		if($dbh->inTransaction()) {
			$dbh->rollBack();
		}
	}
}

// output response
header('Content-Type: application/json');

if(empty($errors)) {
	//no errors we're all gooood
	$response['installed'] = true;
	$response['password'] = $password;
} else {
	$response['installed'] = false;
	$response['errors'] = $errors;
}

// output json formatted string
echo json_encode($response);
