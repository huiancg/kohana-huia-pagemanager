<?php defined('SYSPATH') OR die('No direct script access.');

class Huia_Page_Module {

  public static function format_name($file)
  {
    $pieces = explode(DIRECTORY_SEPARATOR, $file);
    $pieces = array_map('ucfirst', $pieces);
    return join(' - ', $pieces);
  }

  public static function template($file)
  {
    return [
      'id' => md5($file),
      'name' => self::format_name($file),
      'file' => $file,
    ];
  }

  public static function is_partial($file)
  {
    $realfile = explode(DIRECTORY_SEPARATOR, $file);
    $realfile = end($realfile);
    return (strpos($realfile, '_') === 0);
  }

  protected static function map_template($template)
  {
    $file = str_replace(['views/page/block'.DIRECTORY_SEPARATOR, EXT], '', $template);
      
    if (self::is_partial($file))
    {
      return NULL;
    }

    return self::template($file);
  }

  public static function views()
  {
    $templates = Kohana::list_files('views/page/block');
    return array_keys(Arr::flatten($templates));
  }

  public static function templates()
  {
    $templates = self::views();
    $templates = array_map('Page_Module::map_template', $templates);
    return self::format_templates($templates);
  }

  protected static function format_templates($templates)
  {
    $templates = array_filter($templates);
    $names = Arr::pluck($templates, 'name', NULL);
    array_multisort($names, SORT_ASC, SORT_STRING, $templates);
    return $templates;
  }

  public static function template_ids()
  {
    return Arr::pluck(self::templates(), 'id', []);
  }

  public static function find_by_id($template_id)
  {
    $templates = self::templates();
    $template = array_filter($templates, function($template) use ($template_id) {
      return Arr::get($template, 'id') === $template_id;
    });
    return ($template AND ! empty($template)) ? end($template) : [];
  }

  public static function view($template_id, $data = array())
  {
    $view = Arr::get(self::find_by_id($template_id), 'file');
    
    $file = Kohana::find_file('views/page/block', $view);
    if ( ! $file)
    {
      return NULL;
    }

    return View::factory('page/block/' . $view, $data);
  }

}