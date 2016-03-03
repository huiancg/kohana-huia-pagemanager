<?php
if ($preview) {
    $options_shape = array(
        '' => 'Default',
        'img-rounded' => 'Rounded',
        'img-circle' => 'Circle',
        'img-thumbnail' => 'Thumbnail',
        'background' => 'Background',
    );
    echo $helper->option($_data, 'shape', $options_shape, '');
    echo $helper->hidden($_data, 'width', '');
    echo $helper->hidden($_data, 'height', '');
}

$width = Arr::get($_data, 'width', '');
$height = Arr::get($_data, 'height', '');
$shape = Arr::get($_data, 'shape', '');

$styles = array();

if ($width)
{
  $styles['width'] = $width;
}

if ($height)
{
  $styles['height'] = $height;
}

$src = Arr::get($_data, 'image', 'http://lorempixel.com/g/1000/600/');

$attributes = [
  'property' => 'image',
  'property-type' => 'image',
  'src' => $src,
  'class' => ($shape ? '' : 'img-responsive ') . Arr::get($_data, 'shape', ''),
];

if ($shape === 'background')
{
  $attributes['class'] = '';
  $styles['background'] = 'url("' . $src . '") no-repeat 50% 50%';
  $styles['background-size'] = 'cover';
  $styles['width'] = ( ! $width) ? '100%' : $width;
  $styles['height'] = ( ! $height) ? '200px' : $height;
  
  if (isset($attributes['styles']))
  {
    array_walk_recursive($styles, function(&$value, $key) {
      $value = "{$key}: {$value}";
    });
    $attributes['style'] = join(";", $styles);
  }

  echo '<div '. HTML::attributes($attributes) . '></div>';
}
else
{
  if (isset($attributes['styles']))
  {
    array_walk_recursive($attributes['styles'], function(&$value, $key) {
      $value = "{$key}: {$value}";
    });
    $attributes['style'] = join(";", $attributes['styles']);
  }
  echo HTML::image($src, $attributes);
}