<?php
class Registry {

	private static $instance;


	/**
	 * Array config
	 * @var array
	 */
	public $config = array();


	/**
	 * Private construct
	 */
	private function _construct() {}


	public static function getInstance() {

		if(!self::$instance) {
			self::$instance = new Registry;
		}

		return self::$instance;
	}


	/**
	 * Get value
	 * @param  mixed $key
	 * @return mixed
	 */
	public  function get($key) {
		$instance = self::getInstance();
		return isset($instance->config[$key]) ? $instance->config[$key] : null;
	}


	/**
	 * Set value
	 * @param mixed $key
	 * @param mixed $value
	 */
	public  function set($key, $value) {
		$instance = self::getInstance();
		$instance->config[$key] = $value;
	}


	/**
	 * Get array config
	 * @return array
	 */
	public function getConfig() {
		$instance = self::getInstance();
		return $instance->config;
	}
}