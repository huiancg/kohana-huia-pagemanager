<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Base_Page_Block_Template_Container extends Model_App {

  protected $_table_columns = array(
    'id' => array (
			'data_type' => 'int',
			'extra' => 'auto_increment',
			'key' => 'PRI',
			'display' => '11',
		),
    'page_block_template_id' => array (
			'data_type' => 'int',
			'key' => 'MUL',
			'display' => '11',
		),
    'container_id' => array (
			'data_type' => 'int',
			'key' => 'MUL',
			'display' => '11',
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
    'page_block_template' => array('model' => 'Page_Block_Template'),
    'container' => array('model' => 'Container'),
  );

  public function rules()
  {
    return array(
      'page_block_template_id' => array(
        array('numeric'),
        array('not_empty'),
        array('max_length', array(':value', 11)),
      ),
      'container_id' => array(
        array('numeric'),
        array('not_empty'),
        array('max_length', array(':value', 11)),
      ),
    );
  }


  public function labels()
  {
    return array(
      'page_block_template' => __('Page_block_template'),
      'container' => __('Container'),
    );
  }

}