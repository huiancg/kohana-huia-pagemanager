<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Base_Page extends Model_App {

  protected $_table_columns = array(
    'id' => array (
			'data_type' => 'int',
			'extra' => 'auto_increment',
			'key' => 'PRI',
			'display' => '11',
		),
    'id_page' => array (
			'data_type' => 'int',
			'is_nullable' => TRUE,
			'display' => '11',
		),
    'page_category_id' => array (
			'data_type' => 'int',
			'is_nullable' => TRUE,
			'key' => 'MUL',
			'display' => '11',
		),
    'name' => array (
			'data_type' => 'varchar',
			'is_nullable' => TRUE,
			'character_maximum_length' => '128',
		),
    'introduction' => array (
			'data_type' => 'varchar',
			'is_nullable' => TRUE,
			'character_maximum_length' => '128',
		),
    'slug' => array (
			'data_type' => 'varchar',
			'is_nullable' => TRUE,
			'character_maximum_length' => '128',
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
    'data' => array (
			'data_type' => 'blob',
			'is_nullable' => TRUE,
			'character_maximum_length' => '65535',
		),
    'actived' => array (
			'data_type' => 'tinyint',
			'is_nullable' => TRUE,
			'display' => '1',
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

  protected $_has_many = array(
    'links' => array('model' => 'Link'),
    'page_block_templates' => array('model' => 'Page_Block_Template', 'through' => 'page_block_templates_pages'),
  );

  protected $_belongs_to = array(
    'page_category' => array('model' => 'Page_Category'),
  );

  public function rules()
  {
    return array(
      'id_page' => array(
        array('numeric'),
        array('max_length', array(':value', 11)),
      ),
      'page_category_id' => array(
        array('numeric'),
        array('max_length', array(':value', 11)),
      ),
      'name' => array(
        array('max_length', array(':value', 128)),
      ),
      'introduction' => array(
        array('max_length', array(':value', 128)),
      ),
      'slug' => array(
        array('max_length', array(':value', 128)),
      ),
      'title' => array(
        array('max_length', array(':value', 128)),
      ),
      'meta_description' => array(
        array('not_empty'),
        array('max_length', array(':value', 256)),
      ),
      'data' => array(
        array('max_length', array(':value', 65535)),
      ),
      'actived' => array(
        array('numeric'),
        array('max_length', array(':value', 1)),
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
      'id_page' => __('Id_page'),
      'page_category' => __('Page_category'),
      'name' => __('Name'),
      'introduction' => __('Introduction'),
      'slug' => __('Slug'),
      'title' => __('Title'),
      'meta_description' => __('Meta_description'),
      'data' => __('Data'),
      'actived' => __('Actived'),
      'published' => __('Published'),
      'page_block_templates' => __('Page_block_templates'),
    );
  }

}