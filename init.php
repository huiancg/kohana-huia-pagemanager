<?php defined('SYSPATH') or die('No direct script access.');

Route::set('manager_parent', 'manager/<parent>/<parent_id>/<controller>(/<action>(/<id>))', array(
    'id' => '\d+',
    'parent_id' => '\d+',
  ))
  ->defaults(array(
    'controller' => 'page',
    'action'     => 'index',
    'directory' => 'manager'
  ));
  
Route::set('manager', 'manager(/<controller>(/<action>(/<id>)))', array(
    'id' => '\d+'
  ))
  ->defaults(array(
    'controller' => 'page',
    'action'     => 'index',
    'directory' => 'manager'
  ));
  
Route::set('manager_childs', 'manager/<parents>/<controller>(/<action>(/<id>))', array(
    'id' => '\d+',
    'parents' => '(?:(?!(index|edit|new)).)+',
    'controller' => '(?:(?!(index|edit|new)).)+',
  ))
  ->defaults(array(
    'controller' => 'page',
    'action'     => 'index',
    'directory' => 'manager'
  ));

if (ORM_Autogen::db_exists() AND ORM_Autogen::table_exists('pages'))
{
	Model_Page::init_routes();
}