<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Base_Page_Block extends Model_App {

  protected $_table_columns = array(
    'id' => array (
			'data_type' => 'int',
			'extra' => 'auto_increment',
			'key' => 'PRI',
			'display' => '11',
		),
    'page_id' => array (
			'data_type' => 'int',
			'key' => 'MUL',
			'display' => '11',
		),
    'page_block_template_id' => array (
			'data_type' => 'int',
			'key' => 'MUL',
			'display' => '11',
		),
    'data' => array (
			'data_type' => 'blob',
			'is_nullable' => TRUE,
			'character_maximum_length' => '65535',
		),
    'order' => array (
			'data_type' => 'tinyint',
			'is_nullable' => TRUE,
			'display' => '2',
		),
    'updated_at' => array (
			'data_type' => 'datetime',
			'is_nullable' => TRUE,
		),
    'created_at' => array (
			'data_type' => 'datetime',
		),
  );

  protected $_belongs_to = array(
    'page' => array('model' => 'Page'),
    'page_block_template' => array('model' => 'Page_Block_Template'),
  );

  public function rules()
  {
    return array(
      'page_id' => array(
        array('numeric'),
        array('not_empty'),
        array('max_length', array(':value', 11)),
      ),
      'page_block_template_id' => array(
        array('numeric'),
        array('not_empty'),
        array('max_length', array(':value', 11)),
      ),
      'data' => array(
        array('max_length', array(':value', 65535)),
      ),
      'order' => array(
        array('numeric'),
        array('max_length', array(':value', 2)),
      ),
    );
  }


  public function labels()
  {
    return array(
      'page' => __('Page'),
      'page_block_template' => __('Page_block_template'),
      'data' => __('Data'),
      'order' => __('Order'),
    );
  }

}