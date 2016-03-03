<?php
if ($preview) {
    $options_size = array(
        'h1' => 'Gigante',
        'h2' => 'Grande',
        'h3' => 'Normal',
        'h4' => 'Pequeno',
    );
    echo $helper->option($_data, 'size', $options_size, 'h3');

    $options_align = array(
        'text-left' => 'Left',
        'text-center' => 'Center',
        'text-right' => 'Right',
    );
    echo $helper->option($_data, 'align', $options_align, 'text-left');
}
?>
<<?php echo Arr::get($_data, 'size', 'h3'); ?> class="<?php echo Arr::get($_data, 'align', 'text-left'); ?>" property="title">
	<?php echo Arr::get($_data, 'title', 'Title'); ?>
</<?php echo Arr::get($_data, 'size', 'h3'); ?>>