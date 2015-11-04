<script>

	var hide_draft = (el, reload) => {
		el.closest('.draft-container').fadeOut();
		if (reload) {
			window.location.reload();
		}
	}

	var save_draft = (el, id_page, reload) => {
		el = $(el);
		$.post('<?php echo $url; ?>/save_draft/' + id_page).then(function(r) {
			if (r) {
				hide_draft(el, reload);
			}
		}, function() {
			alert('Erro');
		});
	};

	var delete_draft = (el, id_page, reload) => {
		el = $(el);
		if (confirm('Tem certeza que deseja descartar?')) {
			$.post('<?php echo $url; ?>/delete_draft/' + id_page).then(function(r) {
				if (r) {
					hide_draft(el, reload);
				}
			}, function() {
				alert('Erro');
			});
		}
	};
</script>