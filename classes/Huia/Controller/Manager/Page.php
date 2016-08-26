<?php defined('SYSPATH') OR die('No direct script access.');

class Huia_Controller_Manager_Page extends Controller_Manager_App {

  public $ignore_fields = array(
    'data',
  );

  public function before()
  {
    View::set_global('object_options', $this->object_options());
    parent::before();
  }

  public function action_index()
  {
    $this->actions = array(
      array(
        'btn' => 'warning',
        'link' => 'manager/page/preview/:id',
        'icon' => 'object-align-top',
        'text' => 'Layout',
      ),
    );
    
    $this->ignore_fields = array(
      'slug',
      'title',
      'data',
      'object',
      'route',
      'meta_description',
      'keywords',
    );

    if ($this->parent_id AND $this->parent === 'page')
    {
      $this->ignore_fields[] = 'page';
    }

    $this->ignore_actions[] = 'links';

    $this->model->order_by('id');

    parent::action_index();
  }

  public function object_options()
  {
    $models = ORM::get_models();
    $models = array_filter($models, function($model_name) {
      return $model_name !== 'Page';
    });
    $models += [''];
    return json_encode(array_map('strtolower', $models), TRUE);
  }

  public function action_preview()
  {
    $page = Model_Page::factory('Page', $this->request->param('id'));
    View::bind_global('page', $page);
  }

}