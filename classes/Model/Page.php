<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Page extends Model_Base_Page {

	public static $_instance = NULL;

	public static function instance()
	{
		if (Model_Page::$_instance === NULL)
		{
			Model_Page::$_instance = new Model_Page();	
		}
		return Model_Page::$_instance;
	}

	public static function draft($page_id, $blocks)
	{
		$page = Model_Page::factory('Page', $page_id);
		$page->data = @json_encode($blocks, TRUE);
		// $page->published = FALSE;
		try
		{
			$page->save();
		}
		catch (ORM_Validation_Exception $e)
		{
			throw new Exception(join(" | \n",$e->errors('')));
		}
	}

	public function has_draft($id_page)
	{
		$model = Model_Page::factory('Page');
		$model->where('id_page', '=', $id_page);
		$model->where('draft', '=', TRUE);
		$has_draft = (bool) $model->count_all();
		return $has_draft;
	}

	public function link_preview()
	{
		if ( ! $this->loaded())
		{
			return '';
		}

		return URL::site('page/preview/' . $this->id);
	}

	public function link()
	{
		if ( ! $this->loaded())
		{
			return '';
		}

		$prepend = ($this->page_category_id) ? $this->page_category->slug . '/' : '';

		return URL::site($prepend . $this->slug);
	}

	public static function slug_exists($slug)
	{
		return Model_Page::find_by_slug($slug)->loaded();
	}

	public static function find_by_slug($slug)
	{
		// @TODO Aumentar quantidade de níveis
		$exploded = explode('/', $slug, 2);

		$model = Model_Page::factory('Page');

		if (count($exploded) === 2)
		{
			$model->join('page_categories');
			$model->on('page_categories.id', '=', 'page.page_category_id');
			$model->where('page_categories.slug', '=', $exploded[0]);
			$slug = $exploded[1];
		}

		$model->where('page.slug', '=', $slug);
		
		$model->where('page.published', '=', TRUE);
		$model->order_by('page.updated_at', 'DESC');
		return $model->find();
	}

	public function blocks($blocks = array(), $name = NULL, $add_block = TRUE)
	{
		$result = '';
		
		if (count($blocks) AND $blocks)
		{	
			foreach ($blocks as $block)
			{
				$result .= $this->render_block((object) $block, $name);
			}
		}

		if (Auth::instance()->logged_in('admin') AND $add_block)
		{
			$result .= View::factory('page/block/_add', array('render_blocks' => FALSE, 'block_name' => $name));
		}

		return $result;
	}

	public function render_blocks($blocks = NULL)
	{
		if ( ! $this->loaded())
		{
			return '';
		}

		if ($blocks)
		{
			$this->data = $blocks;
		}

		$data = ($blocks) ? $blocks : @json_decode($this->data, TRUE);

		$result = $this->blocks($data);

		if ( ! $result)
		{
			$result = View::factory('page/block/_empty')->render();
		}

		// default
		$before = View::factory('page/_before')->render();
		$after = View::factory('page/_after')->render();

		return $before . $result . $after;
	}

	public function render_block($block, $block_name = NULL)
	{
		$block->data = isset($block->data) ? $block->data : array();
		$block->data = ( ! is_array($block->data)) ? (array) @json_decode($block->data, TRUE) : $block->data;

		$data['_block'] = $block;
		$data['_data'] = $block->data;
		
		$after = View::factory('page/block/_after', $data);
		$view = Model_Page_Block_Template::view($block->page_block_template_id, $data);
		$before = View::factory('page/block/_before', $data);

		// helpers
		$view->helper = Block::factory($block, $block_name, $view);
		
		if ( ! isset($view->page))
		{
			View::bind_global('page', $this);
		}

		return $before->render() . $view->render() . $after->render();
	}

}