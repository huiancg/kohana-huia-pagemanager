<?php
if ($preview) {
    $aligned = array(
        0 => 'Default',
        1 => 'Aligned links',
    );
    echo $helper->option($_data, 'aligned', $aligned, 0);
}
?>
<nav>
  <ul class="pager">
    <li class="<?php echo (Arr::get($_data, 'aligned')) ? 'previous' : ''; ?>">
    	<a href="#"><span aria-hidden="true">&larr;</span> <?php echo __('Previous'); ?></a>
    </li>
    <li class="<?php echo (Arr::get($_data, 'aligned')) ? 'next' : ''; ?>">
    	<a href="#"><?php echo __('Next'); ?> <span aria-hidden="true">&rarr;</span></a>
    </li>
  </ul>
</nav>