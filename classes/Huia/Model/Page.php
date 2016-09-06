<?php defined('SYSPATH') OR die('No direct script access.');

class Huia_Model_Page extends Model_Base_Page {

  public static function init_routes()
  {
    if (self::$_all_routes === NULL)
    {
      self::$_all_routes = Cache::instance()->get('routes');
    }

    $migration = ((Kohana::$environment === Kohana::DEVELOPMENT) AND Arr::get($_GET, 'models'));

    if ((Kohana::$caching === FALSE OR ! self::$_all_routes) AND ! $migration)
    {
      $pages = self::factory('Page')->find_all();
      foreach ($pages as $page)
      {
        $model_name = NULL;
        
        if ($page->object)
        {
          $model_name = self::get_model_name($page->object);    
          
          if  (class_exists('Model_'.$model_name))
          {
            $model = self::factory($model_name);
          }
          else
          {
            continue;
          }

          try
          {
            self::$_all_routes[$page->object] = $model::get_routes();
          }
          catch (Database_Exception $e)
          {
            continue;
          }
        }
        else
        {
          $route = ($page->route) ? $page->route : 'home';
          self::$_all_routes[$route] = (($page->route) ? $page->route : '/');
        }

        if ($model_name)
        {
          self::register_route($model_name, $page->object, $page->route, $page->catcher());
        }
        else
        {
          $object = ($page->route) ? $page->route : 'home';
          $route = ($page->route) ? $page->route : '';
          self::register_route(NULL, $object, $route, $page->catcher());
        }
      }

      Cache::instance()->set('routes', self::$_all_routes);
    }

    return self::$_all_routes;
  }

  public function catcher()
  {
    return 'catcher_'.$this->id;
  }

  public static function register_route($model_name, $object, $route, $catcher)
  {
    $route = Route::set($object, $route, Model_App::all_routes());

    if ($model_name)
    {
      $route->filter('Model_'.$model_name.'::route_filter');
    }

    $route->defaults([
        'controller' => 'page',
        'action'     => 'index',
        'catcher'    => $catcher,
      ]);
  }

  public function find_all_by_published($published = TRUE)
  {
    $this->where('published', '=' , $published);
    return $this->find_all();
  }

  public function published()
  {
    $this->where('published', '=' , TRUE);
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

  public function filter_sitemap()
  {
    $this->where('object', 'IS', NULL);
  }

  public function slug()
  {
    $slug = parent::slug();
    
    if ( ! $this->route AND  ! $this->object)
    {
      return '';
    }
    else if ($this->route)
    {
      return $this->route;
    }

    return $slug;
  }

  public static function find_by_slug($slug)
  {
    return self::factory('Page')
                  ->where('id', '=', (int) substr($slug, 8))
                  ->published()
                  ->find();
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
    $view = Page_Module::view($block->page_block_template_id, $data);
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

  /**
   * Handles getting of column
   * Override this method to add custom get behavior
   *
   * @param   string $column Column name
   * @throws Kohana_Exception
   * @return mixed
   */
  public function get($column)
  {
    $result = parent::get($column);
    if (Request::current() AND Request::current()->directory() !== 'Manager' AND parent::get('object'))
    {
      $result = $this->replace_patters(parent::get('object'), $result);
    }
    return $result;
  }

  protected function replace_patters($object, $string)
  {
    $model = ORM::factory(self::get_model_name($object))->initial();

    if ( ! $model)
    {
      return $string;
    }
    
    $keys = [];
    $values = [];
    foreach ($model->as_array() as $key => $value)
    {
      $keys[] = '<'.$model->object_name().'.'.$key.'>';
      $values[] = $value;
    }
    return str_replace($keys, $values, $string);
  }

}