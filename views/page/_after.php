<?php if (isset($preview) AND $preview) : ?>

  <div id="_block_add_form" title="Adicionar bloco">
    <form>
      <fieldset>
        <label for="page_block_template_id">Template</label>
        <select name="page_block_template_id" id="page_block_template_id">
        	<?php foreach (Model_Page_Block_Template::factory('Page_Block_Template')->find_all_ordened($page->id) as $_template) : ?>
        	<option data-containers='<?php echo json_encode($_template->containers(), true); ?>' value="<?php echo $_template->id; ?>"><?php echo $_template->name; ?></option>
        	<?php endforeach; ?>
        </select>
      </fieldset>
    </form>
  </div>

<?php endif; ?>