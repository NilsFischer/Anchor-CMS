<?php defined('IN_CMS') or die('No direct access allowed.');

class Users {

	public static function authed() {
		return Session::get('user');
	}
	
	public static function list_all($params = array()) {
		$sql = "select * from users where 1 = 1";
		$args = array();
		
		if(isset($params['status'])) {
			$sql .= " and status = ?";
			$args[] = $params['status'];
		}
		
		if(isset($params['sortby'])) {
			$sql .= " order by " . $params['sortby'];
			
			if(isset($params['sortmode'])) {
				$sql .= " " . $params['sortmode'];
			}
		}

		return new Items(Db::results($sql, $args));
	}
	
	public static function find($where = array()) {
		$sql = "select * from users";
		$args = array();
		
		if(isset($where['hash'])) {
			$sql .= " where md5(concat(`id`, `email`, `password`)) = ? limit 1";
			$args[] = $where['hash'];
			
			// reset clause
			$where = array();
		}
		
		if(count($where)) {
			$clause = array();
			foreach($where as $key => $value) {
				$clause[] = '`' . $key . '` = ?';
				$args[] = $value;
			}
			$sql .= " where " . implode(' and ', $clause);
		}
		
		return Db::row($sql, $args);
	}

	public static function login() {
		// get posted data
		$post = Input::post(array('user', 'pass', 'remember'));
		$errors = array();
		
		if(empty($post['user'])) {
			$errors[] = 'Bitte gib deinen Benutzernamen an';
		}
		
		if(empty($post['pass'])) {
			$errors[] = 'Bitte gib dein Passwort ein';
		}

		if(empty($errors)) {
			// find user
			if($user = Users::find(array('username' => $post['user']))) {
				// check password
				if(crypt($post['pass'], $user->password) != $user->password) {
					$errors[] = 'Incorrect details';
				}
			} else {
				$errors[] = 'Incorrect details';
			}
		}
		
		if(count($errors)) {
			Notifications::set('error', $errors);
			return false;
		}
		
		// if we made it this far that means we have a winner
		Session::set('user', $user);
		
		return true;
	}

	public static function logout() {
		Session::forget('user');
	}
	
	public static function recover_password() {
		$post = Input::post(array('email'));
		$errors = array();

		if(filter_var($post['email'], FILTER_VALIDATE_EMAIL) === false) {
			$errors[] = 'Bitte gib eine funktionierende Email-Adresse an.';
		} else {
			if(($user = static::find(array('email' => $post['email']))) === false) {
				$errors[] = 'Account nicht gefunden.';
			}
		}
		
		if(count($errors)) {
			Notifications::set('error', $errors);
			return false;
		}
		
		$hash = hash('md5', $user->id . $user->email . $user->password);
		$link = Url::build(array(
			'path' => Url::make('admin/users/reset/' . $hash)
		));
		
		$subject = '[' . Config::get('metadata.sitename') . '] Password Reset';
		$plain = 'Du hast ein neues Passwort angefordert. Um den Prozess abzuschließen, klicke bitte auf den folgenden Link. ' . $link;
		$headers = array('From' => 'no-reply@' . Input::server('http_host'));
		
		Email::send($user->email, $subject, $plain, $headers);
		
		Notifications::set('notice', 'Wir haben dir eine Email zum Passwort zurücksetzten geschickt.');
		
		return true;
	}
	
	public static function reset_password($id) {
		$post = Input::post(array('password'));
		$errors = array();

		if(empty($post['password'])) {
			$errors[] = 'Bitte gib ein Passwort an';
		}
		
		if(count($errors)) {
			Notifications::set('error', $errors);
			return false;
		}
		
		$password = crypt($post['password']);
		
		$sql = "update users set `password` = ? where id = ?";
		Db::query($sql, array($password, $id));
		
		Notifications::set('success', 'Dein neues Passwort wurde gespeichert');
		
		return true;
	}
	
	public static function delete($id) {
		$sql = "delete from users where id = ?";
		Db::query($sql, array($id));
		
		Notifications::set('success', 'Benutzer wurde gelöscht');
		
		return true;
	}
	
	public static function update($id) {
		$post = Input::post(array('username', 'password', 'email', 'real_name', 'bio', 'status', 'role', 'delete'));
		$errors = array();

		// delete
		if($post['delete'] !== false) {
			return static::delete($id);
		} else {
			// remove it frm array
			unset($post['delete']);
		}
		
		if(empty($post['username'])) {
			$errors[] = 'Bitte gib einen Benutzername an';
		} else {
			if(($user = static::find(array('username' => $post['username']))) and $user->id != $id) {
				$errors[] = 'Benutzername ist schon vergeben';
			}
		}

		if(filter_var($post['email'], FILTER_VALIDATE_EMAIL) === false) {
			$errors[] = 'Bitte gib eine funktionierende Email-Adresse an';
		}

		if(empty($post['real_name'])) {
			$errors[] = 'Bitte gib (d)einen Namen an';
		}
		
		if(strlen($post['password'])) {
			// encrypt new password
			$post['password'] = crypt($post['password']);
		} else {
			// remove it and leave it unchanged
			unset($post['password']);
		}
		
		if(count($errors)) {
			Notifications::set('error', $errors);
			return false;
		}

		// format email
		$post['email'] = strtolower(trim($post['email']));
		
		$updates = array();
		$args = array();

		foreach($post as $key => $value) {
			$updates[] = '`' . $key . '` = ?';
			$args[] = $value;
		}
		
		$sql = "update users set " . implode(', ', $updates) . " where id = ?";
		$args[] = $id;		
		
		Db::query($sql, $args);
		
		// update user session?
		if(Users::authed()->id == $id) {
			Session::set('user', static::find(array('id' => $id)));
		}
		
		Notifications::set('success', 'Benutzer wurde erfolgreich bearbeitet');
		
		return true;
	}

	public static function add() {
		$post = Input::post(array('username', 'password', 'email', 'real_name', 'bio', 'status', 'role'));
		$errors = array();
		
		if(empty($post['username'])) {
			$errors[] = 'Bitte gib einen Benutzername an';
		} else {
			if(static::find(array('username' => $post['username']))) {
				$errors[] = 'Dieser Benutzername ist bereits vergeben';
			}
		}
		
		if(empty($post['password'])) {
			$errors[] = 'Bitte gib ein Passwort ein';
		}

		if(filter_var($post['email'], FILTER_VALIDATE_EMAIL) === false) {
			$errors[] = 'Bitte gib eine funktionierende Email-Adresse an';
		}

		if(empty($post['real_name'])) {
			$errors[] = 'Bitte gib deinen (realen) Namen ein';
		}
		
		if(count($errors)) {
			Notifications::set('error', $errors);
			return false;
		}
		
		// encrypt password
		$post['password'] = crypt($post['password']);
		
		// format email
		$post['email'] = strtolower(trim($post['email']));
		
		$keys = array();
		$values = array();
		$args = array();
		
		foreach($post as $key => $value) {
			$keys[] = '`' . $key . '`';
			$values[] = '?';
			$args[] = $value;
		}
		
		$sql = "insert into users (" . implode(', ', $keys) . ") values (" . implode(', ', $values) . ")";	
		
		Db::query($sql, $args);
		
		Notifications::set('success', 'Ein neuer Benutzer wurde hinzugefügt');
		
		return true;
	}

}
