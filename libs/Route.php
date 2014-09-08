<?php
/**
 * Route mangement
 * @author Justin Luong <cong.itsoft@gmail.com>
 * @Last edit : 2014-08-09
 */
class Route {

	public $_regex = array();

	public $_uri = array();

	public $_regex_current;

	public $_name_route = array();

	public $_callback = array();

	public $_count = -1;

	public $_key = -1;

	const REGEX_CALLBACK = '#([\w]+)@([\w]+)#';


	/**
	 * Add a route
	 *
	 * @param string $regex
	 * @param mixed $handle
	 */
	public function add($regex, $handle) {

		$pattern = $regex;

		$this->_count ++;

		# Get uri
		$uri = isset($_GET['__uri']) ? $_GET['__uri'] : '';
		$uri = explode('/', $uri);

		# Make pattern from route
		$regex = str_replace(array('(:num)', '(:any)'), array('([0-9]*)', '([^/]*)'), $regex);
		$regex = trim($regex, '/');
		$regex = '#' . $regex . '#';

		# Get request uri
		$uri_string = $this->getUri();

		$this->_regex[$this->_count] = $regex;
		$this->_uri = $uri_string;

		// Nếu tham số handle là callback thì gọi callback để thực thi
		// nếu là dạng Class@method ví dụ: IndexController@getIndex thì tách chuỗi và xử lý
		// nếu là dạng mảng array('name' => 'name-route', 'uses' => 'IndexController@getIndex'
		$callback = null;
		if(is_string($handle)) {
			if(!$this->validRegexCallback($handle)) throw new Exception("Parameter 2 must be like example: IndexController@getIndex");
			$callback = $handle;
		}
	  	else if(is_array($handle)) {
	  		if(!isset($handle['name'])) throw new Exception("Please put a name for this route");
	  		if(!isset($handle['uses'])) throw new Exception("Please specific a controller and method for this route");

	  		$callback = $handle['uses'];

	  		$this->_name_route[$handle['name']]['pattern'] = $regex;
	  		$this->_name_route[$handle['name']]['regx_offset'] = array();

	  		preg_match('#(:num)#', $pattern, $matches_num);
			preg_match('#(:any)#', $pattern, $matches_any);

			$matches_num = array_shift($matches_num);
			$matches_any = array_shift($matches_any);

			if($matches_num) {
				$this->_name_route[$handle['name']]['regx_offset'][] = $matches_num;
			}

			if($matches_any) {
				$this->_name_route[$handle['name']]['regx_offset'][] = $matches_any;
			}

	  	}
	  	else if(is_callable($handle)) {
	  		$callback = $handle;
	  	}

	  	$this->_callback[$this->_count]['callback'] = $callback;
	  	$this->_callback[$this->_count]['params'] = array();

		@preg_match($regex, $uri_string, $matches);

		if(isset($matches[0]) && $matches[0] === $uri_string) {
			$this->_regex_current = $matches[0];
			array_shift($matches);
			$this->_callback[$this->_count]['params'] = array_values($matches);
			$this->_key = $this->_count;
		}

	}


	/**
	 * Get URI
	 * @return string
	 */
	public function getUri() {
		$uri_string = isset($_GET['__uri']) ? $_GET['__uri'] : '';
		$uri_string = trim($uri_string, '/');
		return $uri_string;
	}


	/**
	 * Validate callback
	 */
	public function validRegexCallback($string) {
		return preg_match(self::REGEX_CALLBACK, $string);
	}


	/**
	 * Get route by name
	 *
	 * @param  string $name   Name Route
	 * @param  array  $params Params parse to route
	 * @return mixed
	 */
	public function getRouteUser($name, $params = array()) {

		if( array_key_exists($name, $this->_name_route) )
		{

			$route = $this->_name_route[$name];
			$pattern = $route['pattern'];
			$regx_offset = $route['regx_offset'];

			return 'http://' . $_SERVER['HTTP_HOST'] . '/' . trim(str_replace($regx_offset, $params, $pattern), '#');
		}

		return null;
	}


	/**
	 * Run router
	 * @return mixed
	 */
	public function run() {

		# If uri matches with route
		if($this->_uri === $this->_regex_current) {
			$callback = $this->_callback[$this->_key]['callback'];
			$params   = $this->_callback[$this->_key]['params'];

			if (is_callable($callback)) {
				call_user_func_array($callback, $params);
			}
			else {
				$explode = explode('@', $callback);
				$controller = $explode[0];
				$method = $explode[1];
				call_user_func_array(array(new $controller, $method), $params);
			}

		}

	}
}