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

	public function action_save()
	{
		$block = new stdClass();
		$block->data = $this->request->post();
		$block->page_id = $this->request->query('page_id');
		$block->page_block_template_id = $this->request->query('page_block_template_id');

		View::set_global('preview', TRUE);

		$page = Model_Page::factory('Page', $block->page_id);
		View::bind_global('page', $page);

		$result = Model_Page::render_block($block);
		$this->response->body($result);
	}

	public function action_upload()
	{
		$options = array(
			'script_url' => URL::site('page_block/upload') . '/',
			'upload_dir' => DOCROOT . 'public/upload/',
			'upload_url' => URL::site('public/upload') . '/'
		);
		if ($_FILES AND ! isset($_FILES['files']))
		{
			$options['param_name'] = key($_FILES);
		}
		$handler = new UploadHandler($options);
	}

}