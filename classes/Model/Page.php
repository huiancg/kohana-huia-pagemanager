<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Page extends Model_Base_Page {

	public static function draft($page_id, $blocks)
	{
		Database::instance()->begin();

		$result = array();

		try
		{
			$page = Model_Page::factory('Page', $page_id);

			foreach ($page->blocks->find_all() as $block)
			{
				DB::delete('page_blocks')->where('id', '=', $block->id)->execute();
			}

			if ($blocks AND ! empty($blocks))
			{
				foreach ($blocks as $index => $block)
				{
					$model_page_block = Model_Page_Block::factory('Page_Block');
					$model_page_block->order = $index;
					$model_page_block->page_id = $page->id;
					$model_page_block->page_block_template_id = Arr::get($block, 'page_block_template_id');
					$data = Arr::get($block, 'data');
					if ($data AND $data !== 'null')
					{
						// ?
						$data = (is_array($data)) ? Arr::get($data, 0) : $data;
						
						$model_page_block->data = $data;
					}
					$model_page_block->create();
				}
			}

			$result['page'] = $page->all_as_array();

			Database::instance()->commit();
		}
		catch (Kohana_Database_Exception $e)
		{
			Database::instance()->rollback();
			$result['errors'] = $e->getMessage();
		}

		return $result;
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

		return URL::site($this->page_category->slug . '/' . $this->slug);
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

	public function render_blocks()
	{
		if ( ! $this->loaded())
		{
			return '';
		}

		$result = '';
		$blocks = $this->blocks->find_all_ordened();
		if (count($blocks))
		{
			foreach ($blocks as $block)
			{
				$result .= $block->render_view();
			}
		}

		if (Auth::instance()->logged_in('admin'))
		{
			$result .= View::factory('page/block/_add');
		}

		if ( ! $result)
		{
			$result = View::factory('page/block/_empty')->render();
		}

		// default
		$before = View::factory('page/_before')->render();
		$after = View::factory('page/_after')->render();

		return $before . $result . $after;
	}

}