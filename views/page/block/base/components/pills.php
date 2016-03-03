<?php
if ($preview) {
    $options_type = array(
        'pills' => 'Pills',
        'stacked' => 'Stacked',
        'pills nav-stacked' => 'Pills and Stacked',
    );
    echo $helper->option($_data, 'type', $options_type, 'pills');
}
?>
<ul class="nav nav-<?php echo Arr::get($_data, 'type', 'pills') ?>">
	<?php
    $default_pills = [
        [
            'text' => 'Home',
        ],
        [
            'text' => 'Profile',
        ],
        [
            'text' => 'Messages',
        ],
    ];
    ?>
  <?php foreach (Arr::get($_data, 'pills', $default_pills) as $pill) : ?>	
	<li property="pills" property-type="repeat"><?php echo $helper->a($pill, 'text'); ?></li>
	<?php endforeach; ?>
</ul>