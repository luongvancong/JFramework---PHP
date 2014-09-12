<?php

class IndexController extends Controller {

	public function __construct() {
		parent::__construct();
		$this->user = new User;

		var_dump(Input::get('name'));

	}

	public function getIndex() {
		$users = $this->user->all();
		$template = 'index/index.php';
		$this->view->load('layouts/index.php', compact('users', 'template'));
	}

	public function getAddCategory() {
		$this->view->load('category/index.php');
	}

	public function getAbout() {
		echo 'About';
	}

	public function getProduct($id, $name) {
		echo "Product->id :" . json_encode(array($id, $name));
	}
}