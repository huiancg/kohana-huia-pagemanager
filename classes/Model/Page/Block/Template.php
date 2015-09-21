<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Page_Block_Template extends Model_Base_Page_Block_Template {
	
	public function find_all_ordened()
  {
  	return $this->order_by('name', 'ASC')
  							->find_all();
  }

}