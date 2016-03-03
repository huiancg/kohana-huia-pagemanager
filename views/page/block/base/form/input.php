<?php
if ($preview) {
    $options_type = array(
    'text' => 'Text',
    'email' => 'Email',
    'password' => 'Password',
    'date' => 'Date',
  );
    echo $helper->option($_data, 'type', $options_type, 'text');
    echo $helper->hidden($_data, 'placeholder', '');
}
?>
<div class="form-group">
  <label property="label" for="<?php echo URL::slug(Arr::get($_data, 'label', 'Label')); ?>">
    <?php echo Arr::get($_data, 'label', 'Label'); ?>
  </label>
  <input 
      type="<?php echo Arr::get($_data, 'type', 'text'); ?>" 
      class="form-control" 
      id="<?php echo URL::slug(Arr::get($_data, 'label', 'Label')); ?>" 
      placeholder="<?php echo Arr::get($_data, 'placeholder', ''); ?>"
    >
</div>