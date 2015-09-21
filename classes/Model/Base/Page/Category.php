<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Base_Page_Category extends Model_App {

  protected $_table_columns = array(
    'id' => array (
			'data_type' => 'int',
			'extra' => 'auto_increment',
			'key' => 'PRI',
			'display' => '11',
		),
    'name' => array (
			'data_type' => 'varchar',
			'key' => 'UNI',
			'character_maximum_length' => '64',
		),
    'slug' => array (
			'data_type' => 'varchar',
			'character_maximum_length' => '128',
		),
    'description' => array (
			'data_type' => 'text',
			'character_maximum_length' => '65535',
		),
    'image_icon' => array (
			'data_type' => 'varchar',
			'character_maximum_length' => '128',
		),
    'updated_at' => array (
			'data_type' => 'datetime',
			'is_nullable' => TRUE,
		),
    'created_at' => array (
			'data_type' => 'datetime',
		),
  );

  protected $_has_many = array(
    'pages' => array('model' => 'Page'),
  );

  public function rules()
  {
    return array(
      'name' => array(
        array(array($this, 'unique'), array(':field', ':value')),
        array('not_empty'),
        array('max_length', array(':value', 64)),
      ),
      'slug' => array(
        array('not_empty'),
        array('max_length', array(':value', 128)),
      ),
      'description' => array(
        array('not_empty'),
        array('max_length', array(':value', 65535)),
      ),
      'image_icon' => array(
        array('not_empty'),
        array('max_length', array(':value', 128)),
      ),
    );
  }


  public function labels()
  {
    return array(
      'name' => __('Name'),
      'slug' => __('Slug'),
      'description' => __('Description'),
      'image_icon' => __('Image_icon'),
    );
  }

}