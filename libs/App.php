<?php

class App {

	public $view, $model, $request;

	public function __construct() {

		$route = Registry::get('route');

		require_once BASE_PATH . 'app/config/routes.php';
	}

}