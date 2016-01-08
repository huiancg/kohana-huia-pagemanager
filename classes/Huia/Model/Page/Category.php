<?php defined('SYSPATH') OR die('No direct script access.');

class Huia_Model_Page_Category extends Model_Base_Page_Category {

	public function link()
	{
		if ( ! $this->loaded())
		{
			return '';
		}
		return $this->first_page()->link();
	}

	public function first_page()
	{
		return $this->pages->published()->find();
	}

	public function pages()
	{
		return $this->pages->filter_composite()->order_by("id_page")->find_all();
	}

	public static function products_offered()
	{
		$model = Model_Page_Category::factory('Page_Category');
		$model->where('page_category.slug', '!=', '');
		$model->where('page_category.image_icon', '!=', '');

		$model->join(array('pages', 'page'));
		$model->on('page.page_category_id', '=', 'page_category.id');
		$model->where('page.actived', '=', TRUE);
		$model->where('page.published', '=', TRUE);

		$model->group_by('page_category.id');

		return $model->find_all();
	}

	public static function all_pages_by_category($id)
	{
		$model = Model_Page_Category::factory('Page_Category', $id);
		$pages = $model->pages;
		$pages->where('actived', '=', TRUE);
		$pages->where('published', '=', TRUE);
		$pages->where('name', 'NOT LIKE', '% - Interna');
		$pages->order_by('id_page');
		return $pages->find_all();
	}

}