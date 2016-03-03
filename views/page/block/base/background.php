<?php

if ($preview) {
	echo $helper->hidden($_data, 'background-color');
}

$src = Arr::get($_data, 'background');

$styles = [
	'width' => '100%',
	'float' => 'left',
];
$attributes = [];

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

if ($preview)
{
	$styles['padding-top'] = '40px;';
}

if (Arr::get($_data, 'background-color'))
{
	$styles['background-color'] = Arr::get($_data, 'background-color');
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

	<?php echo Model_Page::instance()->blocks(Arr::get($_data, 'container'), 'container'); ?>
</div>