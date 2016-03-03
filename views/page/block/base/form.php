<?php
if ($preview) {
    $options_type = array(
        '' => 'Default',
        'form-inline' => 'Inline',
        'form-horizontal' => 'Horizontal',
    );
    echo $helper->option($_data, 'type', $options_type, '');
}
?>
<form class="<?php echo Arr::get($_data, 'type'); ?>">
	<?php echo Model_Page::instance()->blocks(Arr::get($_data, 'form'), 'form'); ?>
</form>