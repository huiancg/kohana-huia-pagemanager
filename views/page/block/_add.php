<?php if ($preview) : ?>

<div class="<?php echo ((isset($render_blocks) AND $render_blocks)) ? 'container-full' : 'full-width'; ?> _block_add" <?php echo (isset($block_name) AND $block_name) ? 'data-block-name="'. $block_name .'"' : ''; ?>>
	<a href="#">[+] Adicionar bloco</a>
</div>

<?php endif; ?>