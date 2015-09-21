<?php if (Auth::instance()->logged_in('admin')) : ?>
<div id="_block_save">
	<button id="_block_save_btn">Salvar</button>
</div>
<style type="text/css">
	#_block_save {
		position: fixed;
		top: 0;
		left: 0;
		padding: 5px 0;
		background: #333;
		z-index: 9999;
		opacity: 0.95;
		width: 100%;
	}
</style>
<?php endif; ?>