<?php

if ($preview) {
    $options_type = array(
        'default' => 'Default',
        'primary' => 'Primary',
        'success' => 'Success',
        'info' => 'Info',
        'warning' => 'Warning',
        'danger' => 'Danger',
        'link' => 'Link',
    );
    echo $helper->option($_data, 'type', $options_type, 'default');

    $options_size = array(
        'btn-lg' => 'Large',
        '' => 'Default',
        'btn-sm' => 'Small',
        'btn-xs' => 'Extra small',
    );
    echo $helper->option($_data, 'size', $options_size, '');

    $options_width = array(
        'btn-block' => 'Block',
        '' => 'Default',
    );
    echo $helper->option($_data, 'width', $options_width, '');

    $options_state = array(
        'active' => 'Active',
        '' => 'Default',
        'disabled' => 'Disabled',
    );
    echo $helper->option($_data, 'state', $options_state, '');
}

echo $helper->a($_data, 'text', 'Button', [
    'class' => 'btn btn-'.Arr::get($_data, 'type').' '.Arr::get($_data, 'size').' '.Arr::get($_data, 'width').' '.Arr::get($_data, 'state'),
    'role' => 'button',
]);
