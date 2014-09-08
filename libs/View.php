<?php

class View  {

	/**
	 * Load a view
	 *
	 * @param  string $url
	 * @param  array  $params
	 * @return view
	 */
	public function load($url, $params = array()) {
		$file_path = BASE_VIEW . $url;

		// Parse params pass to view
		foreach($params as $key => $value) {
			$$key = $value;
		}

		$route = Registry::get('route');

		if(file_exists($file_path))
			include_once $file_path;
		else
			throw new Exception($url . ' is not exists');

	}
}