<?php defined('SYSPATH') OR die('No direct script access.');

class Huia_Model_Page extends Model_Base_Page {

	public static $_instance = NULL;

	public static function instance()
	{
		if (Model_Page::$_instance === NULL)
		{
			Model_Page::$_instance = new Model_Page();	
		}
		return Model_Page::$_instance;
	}

	public function find_all_by_published($published = TRUE)
	{
		$this->where('published', '=' , $published);
		$this->where('actived', '=', TRUE);
		return $this->find_all();
	}

	public static function draft($page_id, $blocks, $actived = FALSE)
	{
		$page = Model_Page::factory('Page', $page_id);
		$page->data = @json_encode($blocks, TRUE);
		$page->actived = $actived;
		$model_created = $page->save_composite();
		
		if ($actived)
		{
			$model_created->set_composite_actived();
			$model_created->clean_draft();
		}

		return $model_created->as_array();
	}

	public function published()
	{
		$this->where('published', '=' , TRUE);
		$this->where('actived', '=', TRUE);
		$this->order_by('page.name');
		return $this;
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

		if (count($exploded) === 1 AND $exploded[0] === '')
		{
			$model->where_open();
			$model->or_where('page.slug', 'IS', NULL);
			$model->or_where('page.slug', '=', '');
			$model->where_close();
		}
		else
		{
			$model->where('page.slug', '=', $slug);
		}
		
		$model->where('page.published', '=', TRUE);
		$model->where('page.actived', '=', TRUE);
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

		if ( ! $view)
		{
			return View::factory('page/block/_not_found', ['name' => $block_name])->render();
		}

		// helpers
		$view->helper = Block::factory($block, $block_name, $view);
		
		if ( ! isset($view->page))
		{
			View::bind_global('page', $this);
		}

		return $before->render() . $view->render() . $after->render();
	}

}