<?php
/**
 * Created by PhpStorm.
 * User: brandon
 * Date: 1/19/18
 * Time: 11:38 PM
 */

namespace App\Models;

use Core\Database;


class Post {

	public static function getPosts() {
		$posts = Database::queryDb('SELECT * FROM posts');
		return $posts;
	}

}