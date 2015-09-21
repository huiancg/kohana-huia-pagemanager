<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Base_Page_Block_Type extends Model_App {

  protected $_table_columns = array(
    'id' => array (
			'data_type' => 'int',
			'extra' => 'auto_increment',
			'key' => 'PRI',
			'display' => '11',
		),
    'name' => array (
			'data_type' => 'varchar',
			'character_maximum_length' => '64',
		),
    'view' => array (
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
    'page_blocks' => array('model' => 'Page_Block'),
  );

  public function rules()
  {
    return array(
      'name' => array(
        array('not_empty'),
        array('max_length', array(':value', 64)),
      ),
      'view' => array(
        array('not_empty'),
        array('max_length', array(':value', 128)),
      ),
    );
  }


  public function labels()
  {
    return array(
      'name' => __('Name'),
      'view' => __('View'),
    );
  }

}