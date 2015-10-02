<?php if ($preview) : ?>
<style>
  ._block {
    position: relative;
    min-height: 42px;
    width: 100%;
    float: left;
  }
  ._block_toolbar {
    display: none;
    position: absolute;
    top: 5px;
    right: 5px;
    z-index: 9999;
  }
  .btn-add,
  .btn-remove {
    margin-bottom: 10px;
  }
  .data_group,
  .data_group > fieldset {
    border: 1px solid #000;
    padding: 10px;
    margin-bottom: 10px;
  }
</style>
<?php endif; ?>