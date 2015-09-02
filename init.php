<?php defined('SYSPATH') or die('No direct script access.');

Route::set('pagemanager', '<catcher>', array('catcher' => '(?!^(huia|api|manager)).*'))
	->filter(function($route, $params, $request) {
		try
		{
			$exists = Model_Page::slug_exists(Arr::get($params, 'catcher'));
			if ( ! $exists)
			{
				return FALSE;
			}
		} catch (Database_Exception $e) {} 
	})
	->defaults(array(
		'controller'  => 'page',
		'action'      => 'index',
	));