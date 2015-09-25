<?php if ($preview) : ?>
<div class="full-width" id="_block_add" about="null">
	<a href="#">[+] Adicionar bloco</a>
</div>
<div id="_block_add_form" title="Adicionar bloco">
  <form>
    <fieldset>
      <label for="page_block_template_id">Template</label>
      <select name="page_block_template_id" id="page_block_template_id">
      	<?php foreach (Model_Page_Block_Template::factory('Page_Block_Template')->find_all_ordened() as $_template) : ?>
      	<option value="<?php echo $_template->id; ?>"><?php echo $_template->name; ?></option>
      	<?php endforeach; ?>
      </select>
    </fieldset>
  </form>
</div>
<style>
#_block_add.Midgard-midgardEditable-disabled {
	display: none;
}
#_block_add a {
	border: 1px solid #ffffff;
	background: #393939;
	color: #ffffff;
	display: block;
	text-align: center;
	padding: 10px 0;
	margin: 10px 0;
}
</style>
<?php endif; ?>