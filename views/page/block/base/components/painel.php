<?php
if ($preview) {
    $options_type = array(
        'default' => 'Default',
        'primary' => 'Primary',
        'success' => 'Success',
        'info' => 'Info',
        'warning' => 'Warning',
        'danger' => 'Danger',
    );
    echo $helper->option($_data, 'type', $options_type, 'default');
}
?>
<div class="panel panel-<?php echo Arr::get($_data, 'type', 'default'); ?>">
  <div class="panel-heading">
    <h3 class="panel-title" property="title">
    	<?php echo Arr::get($_data, 'title', 'Title'); ?>
    </h3>
  </div>
  <div class="panel-body">
    <?php echo Model_Page::instance()->blocks(Arr::get($_data, 'body'), 'body'); ?>
  </div>

</div>