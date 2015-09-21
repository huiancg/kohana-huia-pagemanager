<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Page_Block extends Model_Base_Page_Block {

	public static function draft($page_id, $page_block_template_id, $order = 0, $data = NULL)
	{
		$model = Model_Page_Block::factory('Page_Block');
		$model->page_id = $page_id;
		$model->page_block_template_id = $page_block_template_id;
		// ?
		$data = (is_array($data)) ? $data[0] : $data;
		$model->data = $data;
		$model->order = $order;
		return $model->render_view();
	}

  public function find_all_ordened()
  {
  	return $this->order_by('order', 'ASC')
  							->find_all();
  }

	public function render_view()
	{
		$template = $this->page_block_template;
		$data = (array) @json_decode($this->data, TRUE);
		$data['_block'] = $this;
		$data['_data'] = $data;
		
		$after = View::factory('page/block/_after', $data);
		$view = View::factory('page/block/' . $template->view, $data);
		$before = View::factory('page/block/_before', $data);

		if ( ! isset($view->page))
		{
			View::bind_global('page', $this->page);
		}

		return $before->render() . $view->render() . $after->render();
	}

}