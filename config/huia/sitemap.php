<?php defined('SYSPATH') OR die('No direct access allowed.');

return [

	// Pages
	[
		'name' => 'Page',
		'callback' => function($model) {
			return $model->object === NULL;
		}
	]

];