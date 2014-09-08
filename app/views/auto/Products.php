<?php

class Products extends Eloquent{

	public $table      = 'products';
	public $primaryKey = 'pro_id';
	public $timestamps = false;

	/**
	 * Url
	 */
	public function getUrl() {

	}
}