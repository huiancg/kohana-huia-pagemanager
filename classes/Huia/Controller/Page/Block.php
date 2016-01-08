<?php defined('SYSPATH') or die('No direct script access.');

class Huia_Controller_Page_Block extends Controller_App {

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

    $result = Model_Page::instance()->render_block($block);
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

  public function action_upload_files()
  {
    $files = array();

    if (isset($_FILES))         
    {     
      foreach($_FILES as $name => $file)
      {
        if (Upload::not_empty($file))
        {
          $filename = uniqid().'_'.$file['name'];
          $filename = preg_replace('/\s+/u', '_', $filename);
          $dir = 'public'.DIRECTORY_SEPARATOR.'upload'.DIRECTORY_SEPARATOR.'page_media';

          create_dir($dir);

          Upload::save($file, $filename, DOCROOT . $dir);

          $files[] = array(
            'url' => URL::site($dir.'/'.$filename),
            'file' => $file,
            'dir' => $dir,
            'filename' => $filename,
          );
        }
      }
    }
    
    $this->response->json(array('files' => $files));
  }

}