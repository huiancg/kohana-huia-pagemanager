<?php
$header_category_name = ($page->page_category_id) ? $page->page_category->name : null;
$header_breadcrumb_title = $header_breadcrumb_level = $page->name;
?>
<ol class="breadcrumb">
  <li><a href="<?php echo URL::site('/'); ?>">Página Inicial</a></li>
  <?php if ($header_category_name) : ?>
  <li><a href="#">Library</a></li>
  <?php endif; ?>
  <li class="active"><?php echo $header_breadcrumb_title; ?></li>
</ol>