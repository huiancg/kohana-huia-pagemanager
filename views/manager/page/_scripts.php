<script>

	var hide_draft = (el) => {
		el.closest('td').find('.btn-draft').fadeOut();
	}

	var save_draft = (el, id_page, page_id) => {
		el = $(el);
		$.post('<?php echo $url; ?>/save_draft/' + id_page).then(function(r) {
			if (r) {
				hide_draft(el);
			}
		}, function() {
			alert('Erro');
		});
	};

	var delete_draft = (el, id_page, page_id) => {
		el = $(el);
		if (confirm('Tem certeza que deseja descartar?')) {
			$.post('<?php echo $url; ?>/delete_draft/' + id_page).then(function(r) {
				if (r) {
					hide_draft(el);
				}
			}, function() {
				alert('Erro');
			});
		}
	};
</script>