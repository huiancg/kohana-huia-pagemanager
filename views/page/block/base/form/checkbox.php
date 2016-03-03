<?php
if ($preview) {
    echo $helper->hidden($_data, 'text', '');

    $options_checked = array(
    '' => 'No',
    'checked' => 'Yes',
  );
    echo $helper->option($_data, 'checked', $options_checked, '');

    $options_type = array(
        'checkbox' => 'Default',
        'checkbox-inline' => 'Inline',
    );
    echo $helper->option($_data, 'type', $options_type, 'checkbox');
}
?>
<div class="<?php echo Arr::get($_data, 'type', 'checkbox'); ?>">
  <label>
    <input type="checkbox" <?php echo Arr::get($_data, 'checked', ''); ?>> <?php echo Arr::get($_data, 'text', 'Checkbox'); ?>
  </label>
</div>