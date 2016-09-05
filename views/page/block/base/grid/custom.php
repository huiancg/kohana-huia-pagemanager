<?php
if ($preview) {
    echo $helper->hidden($_data, 'config', '2 2 2 2 2 2');
}
$config = Arr::get($_data, 'config', '2 2 2 2 2 2');
$items = explode(' ', trim($config));
?>
<div class="row">
  <?php foreach ($items as $index => $item) : ?>
  <div class="col-sm-12 col-md-<?php echo $item; ?>">
    <?php echo Model_Page::factory('Page')->blocks(Arr::get($_data, 'col-'.$index), 'col-'.$index); ?>
  </div>
  <?php endforeach; ?>
</div>