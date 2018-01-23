<?php
/**
 * Created by PhpStorm.
 * User: brandon
 * Date: 1/13/18
 * Time: 6:16 PM
 */

namespace App\Controllers;

use Core\Views;
use App\Models\Post;



class Posts extends ApplicationController {

	/**
	 * Displays the index page for Post
	 */
	public function indexAction() {
		$posts = Post::getPosts();

		Views::render("Posts/Index.php", [
			'posts' => $posts,

		]);
	}

	/**
	 * Displays the addNew page for Post
	 */
	public  function addNewAction() {
		echo "This is addNew action of the Post Controller";
	}

	public function editAction() {
		echo "This is the Post's edit action";
		echo '<p>Route parameters: <pre>' . htmlspecialchars(print_r($this->route_params, true)) . '</pre></p>';
	}

}