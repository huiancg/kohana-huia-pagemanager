<div class="row">
  <div class="col-sm-12 col-md-6">
    <?php echo Model_Page::instance()->blocks(Arr::get($_data, 'col-1'), 'col-1'); ?>
  </div>
  <div class="col-sm-12 col-md-6">
    <?php echo Model_Page::instance()->blocks(Arr::get($_data, 'col-2'), 'col-2'); ?>
  </div>
</div>