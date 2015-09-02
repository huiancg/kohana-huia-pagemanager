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
		//
	}

	public function action_block()
	{
		if ( ! Auth::instance()->logged_in('admin'))
		{
			return;
		}

		$body = (array) @json_decode($this->request->body());
		$subject = Arr::get($body, '@subject');
		
		if ( ! $subject)
		{
			return;
		}
		
		preg_match('@<(\d+)\/(\d+)>@', $subject, $matchs);
		$page_id = $matchs[1];
		$block_id = $matchs[2];
		
		$values = array();
		foreach ($body as $key => $value)
		{
			preg_match('@\<http\:\/\/viejs.org\/ns\/(.*)\>@', $key, $matchs);
			if (empty($matchs))
			{
				continue;
			}
			$key = $matchs[1];
			$values[$key] = $value;
		}

		$data = @json_encode($values);
		$model = Model_Page_Block::factory('Page_Block', $block_id);
		$model->data = $data;
		$model->update();
		$this->response->body('{}');
	}

}