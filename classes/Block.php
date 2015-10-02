<?php defined('SYSPATH') OR die('No direct script access.');

class Block {

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

	public function get($data, $name, $default = NULL)
	{
		return Arr::get($data, $name, $default);
	}

	public function hidden($data, $name, $default = NULL, $attrs = array())
	{
		if ( ! $this->view->preview)
		{
			return;
		}

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
			'property' => 'size',
			'property-type' => 'option',
		);
		$attrs = Arr::merge($default_attrs, $attrs);

		return Form::select($name, $options, $this->get($data, $name, $default), $attrs);
	}

	public function a($data, $name, $default = NULL, $attrs = array())
	{
		$default_attrs = array(
			'property' => $name,
		);
		$attrs = Arr::merge($default_attrs, $attrs);
		$name_link = $name . '_link';
		$title = $this->get($data, $name, $default);
		$href = $this->get($data, $name_link);
		return HTML::anchor($href, $title, $attrs) . $this->hidden($name_link);
	}

	public function image($data, $name, $default = NULL, $attrs = array())
	{
		$default_attrs = array(
			'property' => $name,
			'property-type' => 'image',
		);
		$attrs = Arr::merge($default_attrs, $attrs);
		$href = $this->get($name, $default);
		return HTML::image($href, $attrs);
	}

	public function page_link($data, $name)
	{
		if ( ! $this->view->preview)
		{
			return;
		}
		$html = '<div style="display:none;" property="' . $name . '" property-type="repeat">';
		// return Form::hidden($name, $this->get($name, $default, $attrs), $attrs);
		return $html;
	}

}