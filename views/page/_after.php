<?php if (isset($preview) AND $preview) : ?>

  <div id="_block_add_form" title="Adicionar bloco" data-page-id="<?php echo $page->id; ?>">
    <form>
      <fieldset>
        <label for="page_block_template_id">Template</label>
        <select name="page_block_template_id" id="page_block_template_id">
        	<?php foreach (Page_Module::templates() as $_template) : ?>
        	<option value="<?php echo Arr::get($_template, 'id'); ?>"><?php echo Arr::get($_template, 'name'); ?></option>
        	<?php endforeach; ?>
        </select>
      </fieldset>
    </form>
  </div>

<?php endif; ?>