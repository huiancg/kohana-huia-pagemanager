<div class="btn-group">
	<?php
    $default_buttons = [
        [
            'text' => 'Left',
        ],
        [
            'text' => 'Middle',
        ],
        [
            'text' => 'Right',
        ],
    ];
    ?>
  <?php foreach (Arr::get($_data, 'buttons', $default_buttons) as $button) : ?>	
	<button type="button" class="btn btn-default" property="buttons" property-type="repeat">
		<?php echo $helper->a($button, 'text'); ?>
  </button>
	<?php endforeach; ?>
</div>