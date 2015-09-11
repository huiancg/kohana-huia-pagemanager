<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Page_Block extends Controller_App {

	public function before()
	{
		if ( ! Auth::instance()->logged_in('admin'))
		{
			exit();
		}
		parent::before();
	}

	public function action_index()
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
		
    preg_match('@<(\d+)\/(\d+)\/(\d+|)>@', $subject, $matchs);
		$page_block_template_id = $matchs[1];
		$page_id = $matchs[2];
		$id = $matchs[3];
		
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

		$model = Model_Page_Block::factory('Page_Block', $id);
		$model->data = @json_encode($values);
		$model->page_id = $page_id;
		$model->page_block_template_id = $page_block_template_id;
		$model->save();
		$this->response->body(json_encode($model->as_array()));
	}

	public function action_new()
	{
		$page_id = $this->request->post('page_id');
		$page_block_template_id = $this->request->post('page_block_template_id');
		$this->response->body(Model_Page_Block::draft($page_id, $page_block_template_id));
	}

	public function action_save()
	{
		$page_id = $this->request->post('page_id');
		$blocks = $this->request->post('blocks');

		$result = Model_Page::data_save($page_id, $blocks);

		$this->response->json($result);
	}

}