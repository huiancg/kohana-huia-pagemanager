<?php if (Auth::instance()->logged_in('admin')) : ?>
</div>
<?php endif; ?>

<script>var blocks = blocks || {};
blocks[<?php echo $_block->order; ?>] = {
	page_block_template_id: <?php echo $_block->page_block_template_id; ?>,
	data: (<?php echo json_encode($_block->data); ?>)
};
</script>