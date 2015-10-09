<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Page_Block_Template_Container extends Model_Base_Page_Block_Template_Container {

  protected $_belongs_to = array(
    'page_block_template' => array('model' => 'Page_Block_Template'),
    'container' => array('model' => 'Page_Block_Template'),
  );

  public static function exists($page_block_template_id, $container_id = NULL)
  {
    return (bool) count(Model_Page_Block_Template_Container::container_by_page($page_block_template_id, $container_id));
  }

  public static function remove_template($page_block_template_id, $container_id)
  {
    DB::delete('page_block_template_containers')
            ->where('container_id', '=', $container_id)
            ->where('page_block_template_id', '=', $page_block_template_id)
            ->execute();
  }

  public static function container_ids($page_block_template_id, $container_id = NULL)
  {
    $query = DB::select('container_id')
                        ->from('page_block_template_containers')
                        ->where('page_block_template_id', '=', $page_block_template_id);
    
    if ($container_id)
    {
      $query->where('container_id', '=', $container_id);
    }

    return $query->execute()->as_array(NULL, 'container_id');
  }

  public static function save_template($page_block_template_id, $containers = NULL)
  {
    $ids = Model_Page_Block_Template_Container::container_ids($page_block_template_id);
    $has_containers = $containers AND is_array($containers);
   
    if ($has_containers)
    { 
      foreach ($containers as $container_id)
      {
        $exists = in_array($container_id, $ids);
        if ( ! $exists)
        {
          $model = Model_Page_Block_Template_Container::factory('Page_Block_Template_Container');
          $model->container_id = $container_id;
          $model->page_block_template_id = $page_block_template_id;
          $model->create();
        }
      }
    }

    foreach ($ids as $container_id)
    {
      if ( ! $has_containers OR ! in_array($container_id, $containers))
      {
        Model_Page_Block_Template_Container::remove_template($page_block_template_id, $container_id);
      }
    }
  }

}