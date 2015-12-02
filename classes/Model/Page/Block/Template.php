<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Page_Block_Template extends Model_Base_Page_Block_Template {
	
	public function find_all_ordened($page_id = NULL)
  {
    if ($page_id)
    {
      $valid_ids = Model_Page_Block_Template::valid_template_ids_by_page($page_id);
      if ($valid_ids AND ! empty($valid_ids))
      {
        $this->where('id', 'in', $valid_ids);
      }
    }

  	return $this->order_by('name', 'ASC')
  							->find_all();
  }

  public static function template_ids()
  {
    return DB::select('id')
                ->from('page_block_templates')
                ->execute()
                ->as_array(NULL, 'id');
  }

  public static function template_ids_with_through($page_id = NULL)
  {
    $model = DB::select('page_block_template_id')
                ->from('page_block_templates_pages');

    if ($page_id)
    {
      $model->where('page_id', '=', $page_id);
    }

    return $model->execute()
                ->as_array(NULL, 'page_block_template_id');
  }

  public static function valid_template_ids_by_page($page_id)
  {
    $templates = Model_Page_Block_Template::template_ids();
    $templates_through = Model_Page_Block_Template::template_ids_with_through();
    $valid_templates = Model_Page_Block_Template::template_ids_with_through($page_id);

    $result = array();
    foreach ($templates as $template)
    {
      $has_through = in_array($template, $templates_through);
      $is_valid = in_array($template, $valid_templates);
      if ( ! $has_through OR $is_valid)
      {
        $result[] = $template;
      }
    }

    return $result;
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
      $response[] = (int) $container->container_id;
    }
    return $response;
  }

}