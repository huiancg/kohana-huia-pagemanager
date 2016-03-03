<div class="thumbnail">
  <img src="<?php echo Arr::get($_data, 'image', 'http://lorempixel.com/g/1000/600/'); ?>" property="image" property-type="image">
  
  <?php if ($preview or Arr::get($_data, 'caption')) : ?>
  <div class="caption">
  	<?php echo Model_Page::instance()->blocks(Arr::get($_data, 'caption'), 'caption'); ?>
  </div>
	<?php endif; ?>
</div>