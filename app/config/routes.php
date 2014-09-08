<?php

/*--------------------------------------------------------------------------------------------
* Route register
*--------------------------------------------------------------------------------------------*/

$route->add('/about', array('name' => 'about', 'uses' =>  'IndexController@getAbout'));
$route->add('/san-pham/(:num)-(:any).html', 'IndexController@getProduct');
$route->add('/post/(:num)-(:any).html', array('name' => 'post-detail', 'uses' => 'IndexController@getPost'));
$route->add('/forum', function() {
	echo 'forum';
});
$route->add('/category/add', 'IndexController@getAddCategory');
$route->add('/category/post-add', array('name' => 'post-add-category', 'uses' => 'IndexController@postAddCategory'));

$route->add('/', 'IndexController@getIndex');
$route->add('/auto', 'AutoModuleController@getIndex');
$route->add('/auto-post-step-2', 'AutoModuleController@postIndex');
$route->add('/auto/create', 'AutoModuleController@getCreate');
/*--------------------------------------------------------------------------------------------
* Running Route
*--------------------------------------------------------------------------------------------*/
$route->run();