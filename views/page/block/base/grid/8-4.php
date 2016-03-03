<div class="row">
  <div class="col-xs-6 col-md-8">
    <?php echo Model_Page::instance()->blocks(Arr::get($_data, 'col-1'), 'col-1'); ?>
  </div>
  <div class="col-xs-6 col-md-4">
    <?php echo Model_Page::instance()->blocks(Arr::get($_data, 'col-2'), 'col-2'); ?>
  </div>
</div>