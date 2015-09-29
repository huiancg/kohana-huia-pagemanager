<?php if (isset($preview) AND $preview) : ?>

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
  ._block_add a {
  	border: 1px solid #ffffff;
  	background: #393939;
  	color: #ffffff;
  	text-align: center;
  	padding: 10px 0;
  	margin: 10px 0;
    float: left;
    width: 100%;
  }
  ._block:hover {
    display: block;
    background: rgba(0, 0, 0, 0.2);
  }
  </style>

<?php endif; ?>