<script type="text/javascript">
	var options = <?php echo $object_options; ?>;
	var option_html = '';
	var $object_html = $('<div />');
	var $object = $('input[name="object"]');
	var $route = $('input[name="route"]');
	var $route_label = $('label[for="route"]').closest('div');
	var object_id = $object.val();

	var has_options = ((options.length === 0) || (options[0] !== ''));


	if (has_options) {

		for (var i in options) {
			var selected = options[i] === object_id;
			option_html += '<option '+((selected)?'selected':'')+'>' + options[i] + '</option>';
		}
		$object.replaceWith('<select name="object" class="form-control">'+option_html+'</select>');


		for (var i in options) {
			if (options[i]) {
				var $a = $('<a class="label label-primary" href="#">'+options[i]+'</a>');
				$a.click(function(e) {
						e.preventDefault();
						$route.val($route.val() + '<' + $(this).text() + '>');
				});
				$object_html.append($a).append('&nbsp;');
			}
		}

		$route_label.append($object_html);
	} else {
		$object.closest('div').hide();
	}
</script>