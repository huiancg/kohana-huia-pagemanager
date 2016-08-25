<?php defined('SYSPATH') OR die('No direct script access.');

class Huia_Block {

	protected $block;
	protected $block_name;
	protected $view;

	public static function factory($block, $block_name, $view)
	{
		return new Block($block, $block_name, $view);
	}

	public function __construct($block, $block_name, $view)
	{
		$this->block = $block;
		$this->block_name = $block_name;
		$this->view = $view;
	}

	public function pages($data, $name)
	{
		if ( ! $this->view->preview)
		{
			return;
		}
		
		$pages = Model_Page::factory('Page')->published()->find_all()->as_array('id', 'name');
		return $this->option($data, $name, $pages);
	}

	public function get($data, $name, $default = NULL)
	{
		return Arr::get($data, $name, $default);
	}

	public function hidden($data, $name, $default = NULL, $attrs = array())
	{
		$default_attrs = array(
			'property' => $name,
		);
		$attrs = Arr::merge($default_attrs, $attrs);

		return Form::hidden($name, $this->get($data, $name, $default), $attrs);
	}

	public function option($data, $name, $options, $default = NULL, $attrs = array())
	{
		if ( ! $this->view->preview)
		{
			return;
		}

		$default_attrs = array(
			'style' => 'display:none',
			'property' => $name,
			'property-type' => 'option',
		);
		$attrs = Arr::merge($default_attrs, $attrs);

		return Form::select($name, $options, $this->get($data, $name, $default), $attrs);
	}

	public function select($data, $name, $default = NULL, $attrs = array())
	{
		if ( ! $this->view->preview)
		{
			return;
		}

		$default_attrs = array(
			'style' => 'display:none',
		);
		$attrs = Arr::merge($default_attrs, $attrs);

		$option_selected = [
			0 => 'No',
			1 => 'Yes',
		];

		$html = '<div ' . HTML::attributes($attrs) . '>';

		$options = Arr::get($data, $name);

		foreach ($options as $option)
		{
			$html .= '<div property="' . $name . '" property-type="repeat">';
				$html .= '<span property="text">' . Arr::get($options, 'text', 'Item') . '</span>';
				$html .= '<span property="value">' . Arr::get($options, 'value', '1') . '</span>';
				$html .= $this->option($options, 'selected', $option_selected);
			$html .= '</div>';
		}

		$html .= '</div>';

		return $html;
	}

	public function a($data, $name, $default = NULL, $attrs = array(), $force = FALSE)
	{
		$default_attrs = array(
			'property' => $name,
		);
		if ( ! $force)
		{
			$attrs = Arr::merge($default_attrs, $attrs);
		}
		$name_link = $name . '_link';
		$name_link_internal = $name . '_link_internal';
		$title = ($force) ? $default : $this->get($data, $name, $default);
		$href = $this->get($data, $name_link);
		$href = ($href) ? $href : Model_Page::factory('Page')->published()->where('id', '=', $this->get($data, $name_link_internal))->find()->link();

		return HTML::anchor($href, $title, $attrs) . $this->hidden($data, $name_link) . $this->pages($data, $name_link_internal);
	}

	public function image($data, $name, $default = NULL, $attrs = array())
	{
		$default_attrs = array(
			'property' => $name,
			'property-type' => 'image',
		);
		$attrs = Arr::merge($default_attrs, $attrs);
		$href = $this->get($data, $name, $default);
		return HTML::image($href, $attrs);
	}

	public function file($data, $name, $default = NULL, $attrs = array())
	{
		if ( ! $this->view->preview)
		{
			return;
		}
		
		$default_attrs = array(
			'property' => $name,
			'property-type' => 'file',
		);
		$attrs = Arr::merge($default_attrs, $attrs);
		$href = $this->get($data, $name, $default);
		return Form::hidden($name, $href, $attrs);
	}

}