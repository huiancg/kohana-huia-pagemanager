<?php
if ($preview) {
    $options_type = array(
        'success' => 'Success',
        'info' => 'Info',
        'warning' => 'Warning',
        'danger' => 'Danger',
    );
    echo $helper->option($_data, 'type', $options_type, 'success');
}
?>
<div class="alert alert-<?php echo Arr::get($_data, 'type', 'success'); ?>" role="alert">
	<?php echo Model_Page::factory('Page')->blocks(Arr::get($_data, 'content'), 'content'); ?>
</div>