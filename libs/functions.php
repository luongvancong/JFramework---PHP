<?php

/**
 * Log Function
 * @param  string $filename Ten file
 * @param  string $content  Noi dung
 * @return void
 */
function logs($filename, $content){
	$arrayInfo = debug_backtrace();

	if(isset($arrayInfo[1])) {
		$file = $arrayInfo[1]['file'];
		$line = $arrayInfo[1]['line'];
	}else{
		$file = '--';
		$line = '--';
	}

	$log_path  =   $_SERVER["DOCUMENT_ROOT"] . "/logs/";
	$handle    =   fopen($log_path . $filename . ".cfn", "a");
	//Neu handle chua co mo thêm ../
	if (!$handle) $handle = fopen($log_path . $filename . ".cfn", "a");
	//Neu ko mo dc lan 2 thi exit luon
	if (!$handle) return;

	fwrite($handle,
		"[". date("d/m/Y - G:i:s") . "] " .
		"[" . $file . " on line:" . $line . " ] ".
		"[IP: " . @$_SERVER['REMOTE_ADDR'] . "] Message: " . trim($content) . "\n");
	fclose($handle);
}


/**
 * Check localhost
 *
 * @return boolean
 */
function isLocalhost() {
	return $_SERVER['REMOTE_ADDR'] == '127.0.0.1' ? true : false;
}


/**
 * Make object with data property
 *
 * @param  string $class Class name
 * @param  array  $data  Data array
 * @return mixed
 */
function reflectObject($class, array $data) {
	if(class_exists($class)) {
		$object = new $class;
		foreach($data as $field => $value) {
			$object->$field = $value;
		}

		return $object;
	}

	return null;
}



/**
 * Tạo url filter nhiều tiêu chí
 *
 * @param  string $key   Tên param
 * @param  string $value Giá trị param
 * @return array
 */
function makeUrlFilterMulti($key, $value) {

	if(isset($_GET['page'])) unset($_GET['page']);

	if(isset($_GET[$key]) && $_GET[$key] != '') {
		$key_get = $_GET[$key];

		$key_get_value = explode(':', $key_get);

		// Nếu nằm trong giá trị của $_GET thì unset ngay
		if(in_array($value, $key_get_value)) {
			$k = array_search($value, $key_get_value);
			if($k !== false) {
				unset($key_get_value[$k]);
				$url = url_add_params(array($key => implode(':', $key_get_value)));
				return array('url' => $url, 'active' => 1);
			}
		}
		// Chưa có trong $_GET thì thêm vào
		else{
			array_push($key_get_value, $value);
			$url = url_add_params(array($key => implode(':', $key_get_value)));
			return array('url' => $url, 'active' => 0);
		}
	}

	return array('url' => url_add_params(array($key => $value)), 'active' => 0);
}


function curlGetContent($url) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSLVERSION,3);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,60);
	curl_setopt($ch, CURLOPT_TIMEOUT, 60);
	$data = curl_exec ($ch);
	$error = curl_error($ch);
	curl_close ($ch);
	return $data;
}

function microtime_float(){
   list($usec, $sec) = explode(" ", microtime());
   return ((float)$usec + (float)$sec);
}

function getQuery($query, $params) {
	$keys = array();

	# build a regular expression for each parameter
	foreach ($params as $key => $value) {
	  if (is_string($key)) {
	      $keys[] = '/:'.$key.'/';
	  } else {
	      $keys[] = '/[?]/';
	  }
	}

	$query = preg_replace($keys, $params, $query, 1, $count);

	return $query;
}
