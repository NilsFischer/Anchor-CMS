<?php defined('IN_CMS') or die('No direct access allowed.');

class Pages {

	public static function list_all($params = array()) {
		$sql = "select * from pages where 1 = 1";
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
		
		$uri = Request::uri();
		$pages = array();

		foreach(Db::results($sql, $args) as $page) {
			$page->url = Url::make($page->slug);
			$page->active = (strlen($uri) ? strpos($uri, $page->slug) !== false : $page->slug === 'posts');
			
			$pages[] = $page;
		}

		// return items obj
		return new Items($pages);
	}

	public static function find($where = array()) {
		$sql = "select * from pages";
		$args = array();
		
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
	
	public static function delete($id) {
		$sql = "delete from pages where id = ?";
		Db::query($sql, array($id));
		
		Notifications::set('success', 'Deine Seite wurde gelöscht');
		
		return true;
	}
	
	public static function update($id) {
		$post = Input::post(array('id', 'slug', 'name', 'title', 'content', 'status', 'delete'));
		$errors = array();

		// delete
		if($post['delete'] !== false && $post['id'] != Config::get('metadata.show_posts')) {
			return static::delete($id);
		} else {
			// remove it frm array
			unset($post['delete']);
		}
		
		if(empty($post['name'])) {
			$errors[] = 'Bitte gib einen Namen an';
		}
		
		if(empty($post['title'])) {
			$errors[] = 'Bitte denk dir einen denkwürdigen Titel aus';
		}
		
		if(count($errors)) {
			Notifications::set('error', $errors);
			return false;
		}
		
		if(empty($post['slug'])) {
			$post['slug'] = preg_replace('/\W+/', '-', trim(strtolower($post['name'])));
		}
		
		$updates = array();
		$args = array();

		foreach($post as $key => $value) {
			$updates[] = '`' . $key . '` = ?';
			$args[] = $value;
		}
		
		$sql = "update pages set " . implode(', ', $updates) . " where id = ?";
		$args[] = $id;		
		
		Db::query($sql, $args);
		
		Notifications::set('success', 'Deine Seite wurde bearbeitet');
		
		return true;
	}
	
	public static function add() {
		$post = Input::post(array('slug', 'name', 'title', 'content', 'status'));
		$errors = array();
		
		if(empty($post['name'])) {
			$errors[] = 'Bitte gib einen Namen an';
		}
		
		if(empty($post['title'])) {
			$errors[] = 'Bitte gib einen Titel an';
		}
		
		if(count($errors)) {
			Notifications::set('error', $errors);
			return false;
		}
		
		if(empty($post['slug'])) {
			$post['slug'] = preg_replace('/\W+/', '-', trim(strtolower($post['name'])));
		}

		$keys = array();
		$values = array();
		$args = array();
		
		foreach($post as $key => $value) {
			$keys[] = '`' . $key . '`';
			$values[] = '?';
			$args[] = $value;
		}
		
		$sql = "insert into pages (" . implode(', ', $keys) . ") values (" . implode(', ', $values) . ")";	
		
		Db::query($sql, $args);
		
		Notifications::set('success', 'Deine neue Seite wurde hinzugefügt.');
		
		return true;
	}

}
