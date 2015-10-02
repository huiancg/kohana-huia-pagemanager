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

	public function get($name, $default = NULL)
	{
		return Arr::get($this->block->data, $name, $default);
	}

	public function hidden($name, $default = NULL, $attrs = array())
	{
		if ( ! $this->view->preview)
		{
			return;
		}

		$default_attrs = array(
			'property' => $name,
		);
		$attrs = Arr::merge($default_attrs, $attrs);

		return Form::hidden($name, $this->get($name, $default), $attrs);
	}

	public function option($name, $options, $default = NULL, $attrs = array())
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

		return Form::select($name, $options, $this->get($name, $default), $attrs);
	}

	public function a($name, $default = NULL, $attrs = array())
	{
		$default_attrs = array(
			'property' => $name,
		);
		$attrs = Arr::merge($default_attrs, $attrs);
		$name_link = $name . '_link';
		$title = $this->get($name, $default);
		$href = $this->get($name_link);
		return HTML::anchor($href, $title, $attrs) . $this->hidden($name_link);
	}

	public function page_link($name)
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