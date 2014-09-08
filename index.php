<?php
require_once 'constants.php';

/*-----------------------------------------------------------------------------------------------------------
* Require libraries, controllers, models.. [ DO NOT MODIFY ]
*------------------------------------------------------------------------------------------------------------*/
function findFiles($dir) {
	$root = scandir($dir);
   foreach($root as $value)
   {
      if($value === '.' || $value === '..') {
      	continue;
      }

      if(is_file("$dir/$value")) {
      	$result[] = "$dir/$value";
      	continue;
      }

      foreach(findFiles("$dir/$value") as $value)
      {
         $result[] = $value;
      }
   }
   return $result;
}


function loadLibraries($files) {
	foreach($files as $file) {
		require_once $file;
	}
}


loadLibraries(findFiles(BASE_PATH . 'libs'));
loadLibraries(findFiles(BASE_PATH . 'app/controllers'));
loadLibraries(findFiles(BASE_PATH . 'app/models'));

/*-----------------------------------------------------------------------------------------------------------
* Register Variable [ DO NOT MODIFY ]
*------------------------------------------------------------------------------------------------------------*/
Registry::set('route', new Route);
Registry::set('view', new View);
Registry::set('db', DB::getInstance());

/*-----------------------------------------------------------------------------------------------------------
* Running application [ DO NOT MODIFY ]
*-----------------------------------------------------------------------------------------------------------*/
$app = new App();


