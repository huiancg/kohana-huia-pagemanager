<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Page_Block_Template extends Model_Base_Page_Block_Template {
	
	public function find_all_ordened()
  {
  	return $this->order_by('name', 'ASC')
  							->find_all();
  }

  public static function view($page_block_template_id, $data = array())
  {
  	$view = Model_Page_Block_Template::factory('Page_Block_Template', $page_block_template_id)->view;
  	return View::factory('page/block/' . $view, $data);
  }

  public function containers()
  {
    $response = array();
    $containers = $this->containers->find_all();
    foreach ($containers as $container)
    {
      $response[] = (int) $container->id;
    }
    return $response;
  }

}