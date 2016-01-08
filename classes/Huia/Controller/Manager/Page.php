<?php defined('SYSPATH') OR die('No direct script access.');

class Huia_Controller_Manager_Page extends Controller_Manager_App {

  public $ignore_fields = array(
    'data',
    'actived',
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
      'actived',
      'introduction', 
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

    $this->model->where('actived', '=', TRUE);

    $this->model->order_by('id_page');

    parent::action_index();
  }

  public function action_preview()
  {
    $page = Model_Page::factory('Page', $this->request->param('id'));
    $page = $page->find_last_composite();
    View::bind_global('page', $page);
  }

  protected function save_before()
  {
    $is_new = $this->request->action() === 'new';
    if ($is_new)
    {
      $this->model->id_page = $this->model->id;
      $this->model->actived = TRUE;
    }
  }

}