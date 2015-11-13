<td>
	<?php foreach ($model->has_many() as $name => $values) : ?>
	<?php if (Arr::get($values, 'through') OR in_array($name, $ignore_actions)) { continue; } ?>
	<a class="btn btn-sm btn-success" href="<?php echo $url; ?>/<?php echo $id ?>/<?php echo strtolower(str_replace('_id', '', Arr::get($values, 'model'))); ?>">
		<span class="glyphicon glyphicon-edit"></span> <?php echo __(ucfirst($name)); ?>
	</a>
	<?php endforeach; ?>
	<?php foreach ($actions as $data) : ?>
  <a class="btn btn-sm btn-<?php echo Arr::get($data, 'btn', 'success'); ?>" href="<?php echo __(Arr::get($data, 'link', ''), array(':id' => $id)); ?>">
		<span class="glyphicon glyphicon-<?php echo Arr::get($data, 'icon', 'edit'); ?>"></span> <?php echo Arr::get($data, 'text', ''); ?>
	</a>
  <?php endforeach; ?>

  <?php if (isset($id_page) AND $_row->has_draft()) : ?>
  <div class="draft-container pull-right" style="margin-left: 3px">
		<span class="btn btn-draft btn-sm btn-success" onclick="save_draft(this, <?php echo $id_page; ?>);">
			<span class="glyphicon glyphicon-edit"></span> Salvar Rascunho
		</span>
		<span class="btn btn-draft btn-sm btn-danger" onclick="delete_draft(this, <?php echo $id_page; ?>);">
			<span class="glyphicon glyphicon-trash"></span> Descartar Rascunho
		</span>
	</div>
	<?php endif; ?>
	
	<a class="btn btn-sm btn-primary" href="<?php echo $url; ?>/edit/<?php echo $id ?>">
		<span class="glyphicon glyphicon-edit"></span> Editar
	</a>
	<a class="btn btn-sm btn-danger btn-delete" href="<?php echo $url; ?>/delete/<?php echo $id ?>">
		<span class="glyphicon glyphicon-trash"></span>  Excluir
	</a>
</td>