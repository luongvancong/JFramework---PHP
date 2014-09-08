<?php

class Controller {

	public function __construct() {

		$this->view = Registry::get('view');
		$this->db   = Registry::get('db');

	}


}