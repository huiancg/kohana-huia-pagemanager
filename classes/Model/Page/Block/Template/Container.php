<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Page_Block_Template_Container extends Model_Base_Page_Block_Template_Container {

  protected $_belongs_to = array(
    'page_block_template' => array('model' => 'Page_Block_Template'),
    'container' => array('model' => 'Page_Block_Template'),
  );

}