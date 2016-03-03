<?php
if ($preview) {
    $options_size = array(
    '' => 'Default',
    'pagination-lg' => 'Large',
    'pagination-sm' => 'Small',
  );
    echo $helper->option($_data, 'size', $options_size, '');
}
?>
<nav>
  <ul class="pagination <?php echo Arr::get($_data, 'size', ''); ?>">
    <li>
      <a href="#" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
      </a>
    </li>
    <li><a href="#">1</a></li>
    <li><a href="#">2</a></li>
    <li><a href="#">3</a></li>
    <li><a href="#">4</a></li>
    <li><a href="#">5</a></li>
    <li>
      <a href="#" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
      </a>
    </li>
  </ul>
</nav>