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
		$page_id = $this->request->query('page_id');
		$page_block_template_id = $this->request->query('page_block_template_id');
		$order = $this->request->query('order');
		$block = $this->request->post();

		$result = Model_Page_Block::draft($page_id, $page_block_template_id, $order, (array) @json_encode($block));
		$this->response->body($result);
	}

	public function action_upload()
	{
		$options = array(
			'script_url' => URL::site('page_block/upload') . '/',
      'upload_dir' => SYSPATH . 'public/upload/',
      'upload_url' => URL::site('public/upload') . '/'
		);
		if ($_FILES AND ! isset($_FILES['files']))
		{
			$options['param_name'] = key($_FILES);
		}
		$handler = new UploadHandler($options);
	}

}