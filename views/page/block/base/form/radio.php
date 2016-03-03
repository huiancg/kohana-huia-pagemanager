<?php
if ($preview) {
    echo $helper->hidden($_data, 'name', 'optionsRadios');
    echo $helper->hidden($_data, 'value', '1');
    echo $helper->hidden($_data, 'text', 'Option');

    $options_checked = array(
    '' => 'No',
    'checked' => 'Yes',
  );
    echo $helper->option($_data, 'checked', $options_checked, '');

    $options_type = array(
    'radio' => 'Default',
    'radio-inline' => 'Inline',
  );
    echo $helper->option($_data, 'type', $options_type, 'radio');
}
?>
<div class="<?php echo Arr::get($_data, 'type', 'radio'); ?>">
  <label>
    <input
      type="radio"
      name="<?php echo Arr::get($_data, 'name', 'optionsRadios'); ?>"
      value="<?php echo Arr::get($_data, 'value', '1'); ?>"
      <?php echo Arr::get($_data, 'checked', ''); ?>
      >
    <?php echo Arr::get($_data, 'text', 'Option'); ?>
  </label>
</div>