<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Base_Page extends Model_App {

  protected $_table_columns = array(
    'id' => array (
      'data_type' => 'int',
      'extra' => 'auto_increment',
      'key' => 'PRI',
      'display' => '11',
    ),
    'name' => array (
      'data_type' => 'varchar',
      'is_nullable' => TRUE,
      'character_maximum_length' => '128',
    ),
    'route' => array (
      'data_type' => 'varchar',
      'is_nullable' => TRUE,
      'character_maximum_length' => '256',
    ),
    'title' => array (
      'data_type' => 'varchar',
      'is_nullable' => TRUE,
      'character_maximum_length' => '128',
    ),
    'meta_description' => array (
      'data_type' => 'varchar',
      'character_maximum_length' => '256',
    ),
    'keywords' => array (
      'data_type' => 'varchar',
      'is_nullable' => TRUE,
      'character_maximum_length' => '256',
    ),
    'data' => array (
      'data_type' => 'blob',
      'is_nullable' => TRUE,
      'character_maximum_length' => '65535',
    ),
    'published' => array (
      'data_type' => 'tinyint',
      'is_nullable' => TRUE,
      'display' => '1',
    ),
    'updated_at' => array (
      'data_type' => 'datetime',
      'is_nullable' => TRUE,
    ),
    'created_at' => array (
      'data_type' => 'datetime',
    ),
  );

  public function rules()
  {
    return array(
      'name' => array(
        array('max_length', array(':value', 128)),
      ),
      'route' => array(
        array('max_length', array(':value', 256)),
      ),
      'title' => array(
        array('max_length', array(':value', 128)),
      ),
      'meta_description' => array(
        array('not_empty'),
        array('max_length', array(':value', 256)),
      ),
      'keywords' => array(
        array('max_length', array(':value', 256)),
      ),
      'data' => array(
        array('max_length', array(':value', 65535)),
      ),
      'published' => array(
        array('numeric'),
        array('max_length', array(':value', 1)),
      ),
    );
  }


  public function labels()
  {
    return array(
      'name' => __('Name'),
      'route' => __('Route'),
      'title' => __('Title'),
      'meta_description' => __('Meta_description'),
      'keywords' => __('Keywords'),
      'data' => __('Data'),
      'published' => __('Published'),
    );
  }

}