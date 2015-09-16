<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Page extends Controller_App {

	protected $page = NULL;

	public function before()
	{
		parent::before();
		$this->page = Model_Page::find_by_slug($this->request->param('catcher'));
		View::bind_global('page', $this->page);
		$this->content = $this->page->render_blocks();
	}

	public function action_index()
	{
		$this->title = $this->page->title;
		$this->description = $this->page->meta_description;
	}

	public function action_save()
	{
		if ( ! Auth::instance()->logged_in('admin'))
		{
			exit();
		}

		$page_id = $this->request->post('page_id');
		$blocks = $this->request->post('blocks');

		$result = Model_Page::draft($page_id, $blocks);
		$this->response->json($result);
	}

}