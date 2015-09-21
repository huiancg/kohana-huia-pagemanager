<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Page_Category extends Model_Base_Page_Category {

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
		return $this->pages->find_by_published(TRUE);
	}

	public function pages()
	{
		return $this->pages->find_all_by_published(TRUE);
	}

	public static function products_offered()
	{
		$model = Model_Page_Category::factory('Page_Category');
		// $model->where('published', '=', TRUE);
		$model->where('slug', '!=', '');
		$model->where('image_icon', '!=', '');
		
		return $model->find_all();
	}

}