<div class="form-group">
  
  <label property="label" for="<?php echo URL::slug(Arr::get($_data, 'label', 'Label')); ?>">
    <?php echo Arr::get($_data, 'label', 'Label'); ?>
  </label>

<?php
  $default_options = [
    [
      'text' => 'Item',
      'value' => '1',
      'selected' => '0',
    ],
  ];
  echo $helper->select($_data, 'options', $default_options);
  ?>
  <select class="form-control">
    <?php foreach (Arr::get($_data, 'options', $default_options) as $option) : ?>
    <option
      value="<?php echo Arr::get($option, 'value', '1'); ?>"
      <?php echo (Arr::get($option, 'selected')) ? 'selected' : ''; ?>
      >
      <?php echo Arr::get($option, 'text', 'Item'); ?>
    </option>
    <?php endforeach; ?>
  </select>
</div>