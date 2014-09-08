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

