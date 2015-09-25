<script>

var page_id = $('section.section:first').data('page-id');

var _block_add_form = $('#_block_add_form').dialog({
	autoOpen: false,
	position: {
		my: "center",
		at: "center",
		of: $('#_block_add a')
	},
	buttons: {
		'Adicionar': function() {
			var page_block_template_id = $('#page_block_template_id').val();
			var query = {
				page_id: page_id,
				page_block_template_id: page_block_template_id,
				order: $('._block').size()
			};
			var url = base_url + 'page_block/save?' + $.param(query);
			$.post(url).done(function(r) {
				r = $(r);
				$('#_block_add').before(r);
				bind_toolbar(r);
				_block_add_form.dialog('close');
			});
		},
		'Cancelar': function() {
			_block_add_form.dialog('close');
		}
	}
});

$('#_block_add a').click(function(e) {
	e.preventDefault();
	_block_add_form.dialog('open');
});

var save_page = function() {
	var data = {
		page_id: page_id,
		blocks: blocks
	};
	var url = base_url + 'page/save';
	return $.post(url, data);
}

var save_block = function(form, block) {
	var query = {
		page_id: page_id,
		page_block_template_id: block.data('page-block-template-id'),
		order: block.index('._block')
	};
	var url = base_url + 'page_block/save?' + $.param(query);
	form.find('input[type="file"]').remove();
	return $.post(url, form.serializeArray());
}

var parse_properties = function(block)
{
	block = $(block);
	var properties = block.find('[property]:not([property-type="repeat"] [property])');
	var objects = [];
	_.forEach(properties, function(property) {
		property = $(property);
		
		var key = property.attr('property');
		var type = property.attr('property-type');
		var value = property.html();
		if (type === 'repeat')
		{
			value = parse_properties('<div>' + value + '</div>');
		}
		else if (type === 'image')
		{
			value = property.attr('src');
		}

		var object = {
			type: type,
			element: property,
			key: key,
			value: value
		};

		if (type === 'repeat') {
			var exists = _.find(objects, function(object) {
				return object.key === key;
			});
			if ( ! exists) {
				object['value'] = [value];
				objects.push(object);
			} else {
				objects = _.map(objects, function(object) {
					if (object.key === key) {
						object.value.push(value);
					}
					return object;
				});
			}
			return;
		} else {
			objects.push(object);
		}
	});
	return objects;
}

var render_form = function(properties)
{
	var form = '<form>';
	form += '<fieldset>';
	form += render_fields(properties);
	form += '</fieldset>';
	form += '</form>';
	return $(form);
}

var render_fields = function(properties, name)
{
	var form = '';
	var first = true;
	var open_field = false;
	_.each(properties, function(property, index) {
		var group = name || property['key'];
		var el = property['element'];

		if ($.isArray(property)) {
			group = group + '[' + index + ']';
			form += '<fieldset class="data_index" data-index="' + index + '">';
				form += render_fields(property, group);
				form += '<div>';
					form += '<a class="btn-up"></a>';
					form += '<a class="btn-down"></a>';
					form += '<a class="btn-remove">Remover</a>';
				form += '</div>';
			form += '</fieldset>';
		} else if (property['type'] === 'repeat') {
			if (first) {
				form += '<fieldset class="data_group" data-group="' + group + '">';
				form += '<legend>' + property['key'] + '</legend>';
				open_field = true;
			}
			form += render_fields(property['value'], group);
		} else {
			form += '<label for="' + property['key'] + '">' + property['key'] + '</label>';
			
			var field_name = ((name !== undefined) ? (name + '[' + property['key'] + ']') : property['key']);
			
			if (property['type'] === 'image') {
				var image = el.attr('src')
				
				if (/uploads\//.test(image)) {
					image = image.replace(base_url, '').replace('public/uploads/', '');
					image = base_url + 'public/uploads/thumbnail/' + image;
				}

				form += '<img style="max-width: 100px;" src="' + image + '"><br />';
				form += '<input type="hidden" name="' + field_name + '" value="' + image + '" /><br /><br />';
				form += '<input type="file" name="' + property['key'] + '" value="' + image + '" /><br /><br />';
			} else {
				form += '<input type="text" name="' + field_name + '" value="' + property['value'] + '" />';
			}
		}
		first = false;
	});
	if (open_field) {
		form += '<div><a class="btn-add">Adicionar</a></div>';
		form += '</fieldset>';			
	}
	return form;
}

var fieldset_index = function(fieldset)
{
	var fieldsets = fieldset.children('fieldset');
	var group = fieldset.data('group');
	var regex = new RegExp('^' + group + '\\\[\\d+\\]', 'gi');

	fieldsets.each(function(index, el) {
		el = $(el);
		el.data('index', index);
		el.find('input[type="text"]').each(function() {
			var input = $(this);
			var name = input.attr('name').replace(regex, group + '[' + index + ']');
			input.attr('name', name);
		});
	});
}

var fieldset_reset = function(fieldset)
{
	// clean inputs
	fieldset.find('input[type="text",type="hidden"]').val('');
	fieldset_index(fieldset.parent());
	return fieldset;
}

var modal_render = function(block)
{
	var wando_modal = $('<div title="Editar"></div>');

	var properties = parse_properties(block);

	var form = render_form(properties);

	wando_modal.html(form);

	block.append(wando_modal);
	
	wando_modal.dialog({
		maxHeight: 500,
		modal: true,
		position: {
			my: "center",
			at: "center",
			of: block
		},
		open: function(event, ui) {
			//
		},
		close: function(event, ui) {
			wando_modal.remove();
		},
		buttons: {
			'Aplicar': function() {
				save_block(form, block).done(function(body) {
					body = $(body);
					block.replaceWith(body);
					bind_toolbar(body);
					wando_modal.dialog('close');
				});
			},
			'Cancelar': function() {
				wando_modal.dialog('close');
			}
		}
	});

	wando_modal.find('.btn-up').button({
		icons: {
			primary: 'ui-icon-locker'
		},
		text: false,

	});

	wando_modal.on('click', '.btn-up', function(e) {
		e.preventDefault();
		var group = $(this).closest('fieldset');
		var prev = group.prevAll('fieldset:first');
		if (prev) {
			prev.before(group);
		}
	});

	wando_modal.find('.btn-down').button({
		icons: {
			primary: 'ui-icon-carat-1-s'
		},
		text: false
	});

	wando_modal.on('click', '.btn-down', function(e) {
		e.preventDefault();
		var group = $(this).closest('fieldset');
		var next = group.nextAll('fieldset:first');
		if (next) {
			next.after(group);
		}
	});

	wando_modal.find('.btn-add').button();

	wando_modal.on('click', '.btn-add', function(e) {
		e.preventDefault();
		var group = $(this).closest('fieldset');
		var clone = group.find('fieldset:first').clone();
		$(this).closest('div').before(clone);
		fieldset_reset(clone);
	});

	wando_modal.find('.btn-remove').button();

	wando_modal.on('click', '.btn-remove', function(e) {
		e.preventDefault();
		var group = $(this).closest('fieldset');
		var parent = group.parent();
		if (parent.children('fieldset').size() === 1) {
			fieldset_reset(group);
		} else {
			group.remove();
			fieldset_index(parent);
		}
	});

	// Upload
	$('input[type="file"]', wando_modal).fileupload({
		dataType: 'json', 
		url: base_url+'page_block/upload',
		imageMaxWidth: 800,
    imageMaxHeight: 800,
    imageCrop: true,
    acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
		done: function (e, data) {
			var that = $(this);
			var image = data.response().result[that.attr('name')][0];
      var src = image.url;
      var thumb = image.thumbnailUrl;
      var img = that.prevAll('img:first');
      var hidden = that.prevAll('input[type="hidden"]:first');
			hidden.val('public/upload/' + image.name);
			img.replaceWith('<img src="' + thumb + '"><br />');
			that.data('change-value', true);
			that.data('value', image.name);
			window.that = that;
  	}
  })
}

var bind_block = function(block) {
	var properties = block.find('[property]');
	if ( ! properties.size()) {
		block.find('._block_toolbar_edit').hide();	
	}
}

var bind_toolbar = function(block)
{
	block_index_refresh();

	if (block) {
		app.block.start(block);
		bind_block(block);
	} else {
		$('._block').each(function() {
			bind_block($(this));
		})
	}

	var toolbar_buttons = $('._block_toolbar_button', block);

	toolbar_buttons.filter('._block_toolbar_up').button({
		icons: {
			primary: 'ui-icon-locker'
		},
		text: false
	}).click(function(e) {
		e.preventDefault();

		var block = $(this).closest('._block');
		var prevBlock = block.prevAll('._block:first');
		if (prevBlock.size()) {
			block.prevAll('._block:first').before(block);
			
			var index = block.index('._block') + 1;
			var current_data = blocks[index];
			var prev_data = blocks[index - 1];

			blocks[index] = prev_data;
			blocks[index - 1] = current_data;
		}
	});

	toolbar_buttons.filter('._block_toolbar_down').button({
		icons: {
			primary: 'ui-icon-carat-1-s'
		},
		text: false
	}).click(function(e) {
		e.preventDefault();

		var block = $(this).closest('._block');
		var nextBlock = block.nextAll('._block:first');
		if (nextBlock.size()) {
			block.nextAll('._block:first').after(block);
			
			var index = block.index('._block') - 1;
			var current_data = blocks[index];
			var next_data = blocks[index + 1];

			blocks[index] = next_data;
			blocks[index + 1] = current_data;
		}
	});;

	toolbar_buttons.filter('._block_toolbar_delete').button({
		icons: {
			primary: 'ui-icon-trash'
		},
		text: false
	}).click(function(e) {
		e.preventDefault();
		if (confirm('Deseja apagar?')) {
			var block = $(this).closest('._block');
			block_delete(block);
		}
	});;

	toolbar_buttons.filter('._block_toolbar_edit').button({
		icons: {
			primary: 'ui-icon-pencil'
		},
		text: false
	}).click(function(e) {
		e.preventDefault();
		modal_render($(this).closest('._block'));
	});
}

var block_delete = function(block)
{
	var index = block.index('._block');
	block_index_remove(index);
	block.remove();
}

var block_index_remove = function(index)
{
	blocks = _.reject(blocks, function(item, idx) {
		return idx == index;
	});
	block_index_refresh();
}

var block_index_refresh = function()
{
	var index = 0;
	blocks = _.object(_.map(blocks, function (value, key) {
		return [index++, value];
	}));
}

bind_toolbar();

</script>