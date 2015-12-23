<?php if (isset($preview) AND $preview) : ?>

<script>window.jQuery || document.write('<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"><\/script>')</script>
<script>window._ || document.write('<script src="//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"><\/script>')</script>
<script>window.Backbone || document.write('<script src="//cdnjs.cloudflare.com/ajax/libs/backbone.js/1.2.2/backbone-min.js"><\/script>')</script>

<script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

<script src="//cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
<script src="//blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js"></script>
<script src="//blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>

<script src="//cdn.ckeditor.com/4.4.7/standard/ckeditor.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.5.7/jquery.fileupload.min.js"></script>

<style>
	._block {
		position: relative;
	}
	._block ._edit_buttons {
		position: absolute;
		top: 5px;
		right: 5px;
		z-index: 9999;
	}
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
		z-index: 5;
	}
	._block:hover > ._block_toolbar {
		display: block;
	}
        
        .wando-modal label {
            display: block;
            margin-bottom: .5rem;
            font-weight: 600;
        }
        
        .wando-modal input[type=text], 
        .wando-modal input[type=email], 
        .wando-modal input[type=number], 
        .wando-modal select, 
        .wando-modal textarea {
            color: #666;
            font: 16px;
            font-size: 13px;
            height: 30px;
            margin-bottom: 1.25rem;
            padding: 6px 10px;
            background-color: #fff;
            border: 1px solid #d7dcde;
            width: 100%;
        }
</style>

<script>

'use strict';

CKEDITOR.config.allowedContent = true;

var edited = false;
var saving = false;
var set_edited = function(status, draft) {
	edited = (status !== undefined) ? status : true;
};

$(window).bind('beforeunload', function(e) {
	if (edited) {
		return 'As alterações serão perdidadas caso saia da página.';
	}
});

var page_id = parseInt($('#_block_add_form').data('page-id'));

var _block_add = $('section.section > ._block_add');

var _block_add_form = $('#_block_add_form').dialog({
	autoOpen: false,
	position: {
		my: "center",
		at: "center",
		of: _block_add
	},
	open: function(evt) {
		$('#page_block_template_id').val($('#page_block_template_id option:first').val());
	},
	buttons: {
		'Adicionar': function() {
			var that = $(this);
			var page_block_template_id = $('#page_block_template_id').val();
			if ( ! page_block_template_id) {
				return alert('Selecione um template.');
			}
			var query = {
				page_id: page_id,
				page_block_template_id: page_block_template_id
			};
			var url = base_url + 'page_block/save?' + $.param(query);
			$.post(url).done(function(r) {
				r = $(r);
				_block_add.before(r);
				bind_toolbar(r);
				_block_add_form.dialog('close');
				set_edited();
			});
		},
		'Cancelar': function() {
			_block_add_form.dialog('close');
		}
	}
});

var _block_add_select = _block_add_form.find('select[name="page_block_template_id"]');

var get_sub_blocks = function(block) {
	var adds = filter_sub_elements(block, block.find('._block_add'));
	if ( ! adds.size()) {
		return null;
	}
	var blocks = {};
	adds.each(function() {
		var that = $(this);
		var block_name = that.data('block-name');
		var _blocks = that.parent().find('._block');

		var block_id = that.closest('._block').attr('id');
		_blocks = _blocks.filter(function() {
			// lolz
			return $(this).parents('._block').attr('id') === block_id;
		});
		
		blocks[block_name] = [];
		_blocks.each(function() {
			var _block = $(this);
			var sub_blocks = get_sub_blocks(_block);
			var _data =_.extend({}, window[_block.attr('id')], sub_blocks);
			blocks[block_name].push({
				data: _data,
				page_block_template_id: _block.data('page-block-template-id')
			});
		});
	});
	return blocks;
}

var get_blocks = function() {
	var blocks = [];
	$('._block:not(._block ._block)').each(function(index, el) {
		var block = $(el);
		var sub_blocks = get_sub_blocks(block);
		var _data = _.extend({}, window[block.attr('id')], sub_blocks);
		var data = {
			data: _data,
			page_id: page_id,
			page_block_template_id: block.data('page-block-template-id')
		};
		blocks.push(data);
	});
	return blocks;
}

$(document).on('click', '._block_add a', function(e) {
	e.preventDefault();
	var that = $(this);
	_block_add = that.closest('._block_add');
	filter_containers(that);
	_block_add_form.dialog('open');
});

var filter_containers = function(el) {
	var select = _block_add_select.clone();
	var options = select.find('option');
	
	var current_block = el.closest('._block');
	var template_id = current_block.data('page-block-template-id');
	var first_level = ! template_id;

	_.each(options, function(option) {
		option = $(option);
		
		var containers = option.data('containers');

		var has_containers = containers.length;

		var valid_containers = _.find(containers, function(i) {
			return i == template_id;
		});

		var invalid;
		
		if (first_level) {
			invalid = has_containers;
		} else if (has_containers) {
			invalid =  ! valid_containers;
		}

		if (invalid) {
			option.remove();
		}
	});

	_block_add_form.find('select[name="page_block_template_id"]').replaceWith(select);
}

var save_page = function(draft) {
	
	if (saving) {
		return;
	}

	saving = true;

	var data = {
		page_id: page_id,
		blocks: get_blocks(),
		draft: (draft === true)
	};
	
	var url = base_url + 'page/save';
	
	function get_preview()
	{
		var def = $.Deferred();
		var add_buttons = $('._block_add').hide();
		html2canvas($('body'), {
			onrendered: function(canvas) {
				var jpeg = canvas.toDataURL("image/jpeg");
				add_buttons.show();
				def.resolve('success', jpeg)
		  }
		});
		return def.promise();
	}

	var preview = get_preview();

	var result = $.when(get_preview());
	
	return result.then(function(success, image) {
			data.image = image;
			return $.post(url, data).success(function() {
				set_edited(false, draft);
				saving = false;
			});
		});
}

var save_block = function(form, block) {
	var query = {
		page_id: page_id,
		page_block_template_id: block.data('page-block-template-id')
	};
	var url = base_url + 'page_block/save?' + $.param(query);
	form.find('input[type="file"]').remove();
	var sub_data = get_sub_blocks(block);
	var data = form.serializeArray();
	data = _.object(_.pluck(data, 'name'), _.pluck(data, 'value'));
	data = _.extend({}, data, sub_data);
	return $.post(url, data);
}

var filter_sub_elements = function(block, elements)
{
	var block_id = block.attr('id');
	return elements.filter(function(index, el) {
		var inner_block = $(el).closest('._block');
		var inner_block_id = inner_block.attr('id');
		return inner_block_id === block_id;
	});
}

var parse_properties = function(block)
{
	block = $(block);
	var properties = block.find('[property]:not([property-type="repeat"] [property], .cloned)');
	properties = filter_sub_elements(block, properties);
	var objects = [];
	_.forEach(properties, function(property) {
		property = $(property);
		
		var key = property.attr('property');
		var type = property.attr('property-type');
		var value = property.html();
		var node_name = property.prop('nodeName').toLowerCase();
		if (type === 'repeat')
		{
			value = parse_properties('<div>' + value + '</div>');
		}
		else if (type === 'image' || node_name === 'img')
		{
			value = property.attr('src');
		}
		else if (node_name === 'input')
		{
			value = property.val();
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
			if ((/\[.*\]/).test(group)) {
				group = group + '[' + property['key'] + ']';
			}
			form += '<fieldset class="data_group" data-group="' + group + '">';
			form += '<legend>' + property['key'] + '</legend>';

			form += render_fields(property['value'], group);
			
			form += '<div><a class="btn-add">Adicionar</a></div>';
			form += '</fieldset>';
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
			} else if (property['type'] === 'file') {
				form += '<input type="hidden" name="' + field_name + '" value="' + $.trim(property['value']) + '" />';
				form += '<input type="file" name="' + property['key'] + '" value="' + $.trim(property['value']) + '" /><br /><br />';
			} else if (property['type'] === 'text') {
				form += '<textarea class="ckeditor" name="' + field_name + '">' + $.trim(property['value']) + '</textarea>';
			} else if (property['type'] === 'option') {
				form += '<select name="' + field_name + '">' + $.trim(property['value']) + '</select>';
			} else {
				form += '<input type="text" name="' + field_name + '" value="' + $.trim(property['value']) + '" />';
			}
		}
	});
	return form;
}

var fieldset_index = function(fieldset)
{
	var fieldsets = fieldset.children('fieldset');
	var group = fieldset.data('group');
	var regex = new RegExp('^' + group + '\\\[\\d+\\]', 'gi');

	fieldsets.each(function(index, el) {
		el = $(el);
		el.attr('data-index', index);
		el.find('input[type="text"],input[type="hidden"],textarea.ckeditor').each(function() {
			var input = $(this);
			var name = input.attr('name').replace(regex, group + '[' + index + ']');
			input.attr('name', name);
			if(input.hasClass("ckeditor") && !input.parent().find('.cke_reset').length){
				CKEDITOR.replace(input.attr("name"), {
	    			toolbar: [
							{ name: 'basicstyles', items: [ 'Bold', 'Italic', 'Strike', '-', 'RemoveFormat' ] },
							{ name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
							{ name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'SpecialChar' ] },
							'/',
							{ name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote' ] },
							{ name: 'document', items: [ 'Source' ] }
						]
	    	});
			}
		});
	});
}

var fieldset_reset = function(fieldset)
{
	// clean inputs
	fieldset.find('input[type="text"],input[type="hidden"],textarea').val('');

	fieldset.find('.cke_reset').remove();
	fieldset_index(fieldset.parent());
	return fieldset;
}

var modal_render = function(block)
{
	var wando_modal = $('<div title="Editar" class="wando-modal"></div>');

	var properties = parse_properties(block);

	var form = render_form(properties);

	wando_modal.html(form);

	block.append(wando_modal);
	
	wando_modal.dialog({
		height: 'auto',
		width: 'auto',
		resize: 'auto',
		maxHeight: 500,
		modal: true,
		position: {
			my: "center",
			at: "center",
			of: block
		},
		open: function(event, ui) {
			$('textarea.ckeditor', wando_modal).each(function() {
				CKEDITOR.replace(this, {
	    			toolbar: [
							{ name: 'basicstyles', items: [ 'Bold', 'Italic', 'Strike', '-', 'RemoveFormat' ] },
							{ name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
							{ name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'SpecialChar' ] },
							'/',
							{ name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote' ] },
							{ name: 'document', items: [ 'Source' ] }
						]
	    	});
			});
		},
		close: function(event, ui) {
			wando_modal.remove();
		},
		buttons: {
			'Aplicar': function() {
				$('textarea.ckeditor', wando_modal).each(function () {
					var $textarea = $(this);
					$textarea.val(CKEDITOR.instances[$textarea.attr('name')].getData());
				});
				save_block(form, block).done(function(body) {
					body = $(body);
					block.replaceWith(body);
					bind_toolbar(body);
					set_edited();
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
	var properties = filter_sub_elements(block, block.find('[property]'));
	if ( ! properties.size()) {
		block.find('._block_toolbar_edit:first').hide();	
	}
}

var bind_toolbar = function(block)
{
	if (block) {
		$('.block', block).each(function() {
			if ( ! window.block_starter) {
				alert('Você precisa setar um iniciador de blocks. "block_starter"');
				return;
			}
			block_starter($(this));
		});
		bind_block(block);
	} else {
		$('._block').each(function() {
			bind_block($(this));
		});
	}

	// remove href functions
	$('a', block).click(function(e) {
		e.preventDefault();
	});

	// remove form submit functions
	$('form', block).submit(function(e) {
		e.preventDefault();
	});

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
			set_edited();
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
			set_edited();
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
	block.remove();
	set_edited();
}

bind_toolbar();

</script>
<?php endif; ?>