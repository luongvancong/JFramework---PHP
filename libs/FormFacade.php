<?php


class FM {

	public static $_instance;

	public $_form;

	public static function getInstance() {
		if(!self::$_instance) {
			self::$_instance = new self;
		}

		$instance = self::$_instance;

		if(!$instance->_form) {
			$instance->_form = new FormMaker;
		}

		return self::$_instance;
	}

	public static function __callStatic($method, $params) {

		$instance = self::getInstance();
		$form = $instance->_form;

		if(in_array($method, array_merge($form->getMethods(), get_class_methods($form))))
		{
			return call_user_func_array(array($form, $method), $params);
		}
		else
		{
			$class = get_class();
			throw new Exception("Class $class with method $method is not exist");
		}
	}

	private function __construct() {}
	private function __clone() {}
}