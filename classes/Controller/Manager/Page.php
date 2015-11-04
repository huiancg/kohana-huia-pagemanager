<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Manager_Page extends Controller_Manager_App {

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
		$page = Model_Page::find_last_by_id_page($page->id_page);
		View::bind_global('page', $page);
	}

	public function action_save_draft()
	{
		$this->template = NULL;
		
		$id = $this->request->param('id');
		
		if ($this->request->method() !== Request::POST OR ! $id)
		{
			return;
		}

		$this->response->json(Model_Page::set_draft_actived($id));
	}

	public function action_delete_draft()
	{
		$this->template = NULL;
		
		$id = $this->request->param('id');
		
		if ($this->request->method() !== Request::POST OR ! $id)
		{
			return;
		}

		$this->response->json(Model_Page::clean_draft($id));
	}

}