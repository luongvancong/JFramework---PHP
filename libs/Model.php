<?php

abstract class Model {

	protected $primaryKey;

	protected $table;

	public function __construct() {
		$this->db = Registry::get('db');
	}

	public function find($id) {
		return $this->db->query("SELECT * FROM " . $this->table . " WHERE " . $this->primaryKey . "=" . $id . " LIMIT 1")->fetch();
	}

	public function all() {
		return $this->db->query("SELECT * FROM " . $this->table)->fetchAll();
	}
}