<?php
if ($preview) :
$uid = '_block_' .md5(uniqid(rand(), TRUE));
?>
<div id="<?php echo $uid; ?>" class="_block" 
		data-page-block-template-id="<?php echo $_block->page_block_template_id; ?>">
	<script type="text/javascript">
	var <?php echo $uid; ?> = <?php echo json_encode($_block->data); ?>;
	</script>
  <div class="_block_toolbar">
    <a href="" class="_block_toolbar_button _block_toolbar_up"></a>
    <a href="" class="_block_toolbar_button _block_toolbar_down"></a>
    <a href="" class="_block_toolbar_button _block_toolbar_edit">Editar</a>
    <a href="" class="_block_toolbar_button _block_toolbar_delete">Excluir</a>
  </div>
<?php endif; ?>