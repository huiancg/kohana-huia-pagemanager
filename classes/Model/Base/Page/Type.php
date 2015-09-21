<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Base_Page_Type extends Model_App {

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
    'updated_at' => array (
			'data_type' => 'datetime',
			'is_nullable' => TRUE,
		),
    'created_at' => array (
			'data_type' => 'datetime',
		),
  );

  protected $_has_many = array(
    'page_block_types' => array('model' => 'Page_Block_Type'),
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
    );
  }


  public function labels()
  {
    return array(
      'name' => __('Name'),
    );
  }

}