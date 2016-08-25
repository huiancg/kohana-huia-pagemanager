<?php defined('SYSPATH') OR die('No direct script access.');

class Huia_Controller_Manager_Page extends Controller_Manager_App {

  public $ignore_fields = array(
    'data',
  );

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
      'meta_description',
    );

    if ($this->parent_id AND $this->parent === 'page')
    {
      $this->ignore_fields[] = 'page';
    }

    $this->ignore_actions[] = 'links';

    $this->model->order_by('id');

    parent::action_index();
  }

  public function action_preview()
  {
    $page = Model_Page::factory('Page', $this->request->param('id'));
    View::bind_global('page', $page);
  }

}