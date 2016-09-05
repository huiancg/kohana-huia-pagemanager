<?php
if ($preview) {
  $options_type = array(
    'container' => 'Fixed',
    'container-fluid' => 'Fluid',
  );
  echo $helper->option($_data, 'type', $options_type, 'container');
  echo $helper->hidden($_data, 'font-color');
}

$src = Arr::get($_data, 'background');

$attributes = [
  'class' => Arr::get($_data, 'type', 'container'),
];

$styles = [];

if ($src)
{
	$styles['background'] = 'url("' . $src . '") no-repeat 50% 50%';
	$styles['background-size'] = 'cover';
}

$font_color = Arr::get($_data, 'font-color');
if ($font_color)
{
	$styles['color'] = $font_color;
}

array_walk_recursive($styles, function(&$value, $key) {
  $value = "{$key}: {$value}";
});
$attributes['style'] = join(";", $styles);

?>
<div <?php echo HTML::attributes($attributes); ?>>
	
	<?php if ($preview) : ?>
		<img src="<?php echo $src; ?>" style="display:none" property="background" property-type="image">
	<?php endif; ?>

	<?php echo Model_Page::factory('Page')->blocks(Arr::get($_data, 'container'), 'container'); ?>
</div>