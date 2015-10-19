<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Page extends Controller_App {

	protected $page = NULL;

	protected $preview = FALSE;

	public function before()
	{
		parent::before();
		
		View::bind_global('preview', $this->preview);
		
		if ($this->request->action() === 'index')
		{
			$this->page = Model_Page::find_by_slug($this->request->param('catcher'));
		}
		else
		{
			if ( ! Auth::instance()->logged_in('admin'))
			{
				exit();
			}
			$this->preview = TRUE;
			$this->page = Model_Page::factory('Page', $this->request->param('id'));
		}

		View::bind_global('page', $this->page);
		$this->content = $this->page->render_blocks();
	}

	public function after()
	{
		parent::after();
	}

	public function action_index()
	{
		$this->title = $this->page->title;
		$this->description = $this->page->meta_description;
	}

	public function action_preview()
	{
		$this->title = $this->page->title;
		$this->description = $this->page->meta_description;
	}

	public function base64_to_jpeg($base64_string, $output_file)
	{
		$ifp = fopen($output_file, "wb");
		$data = explode(',', $base64_string);
		fwrite($ifp, base64_decode($data[1])); 
		fclose($ifp);
		return $output_file; 
	}

	public function save_image($page_id, $image)
	{
    $dir = DOCROOT.'public'.DIRECTORY_SEPARATOR.'upload'.DIRECTORY_SEPARATOR.'page_preview'.DIRECTORY_SEPARATOR;
    create_dir($dir);
    $file = $dir . $page_id . '.jpg';
    $this->base64_to_jpeg($image, $file);
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
		
		$image = $this->save_image($page_id, $this->request->post('image'));

		$this->response->json($result);
	}

}