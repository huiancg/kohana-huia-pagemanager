
<nav id="buttons-navbar" class="navbar navbar-default navbar-fixed" data-spy="affix">
  <div class="container-fluid">

		<ol class="breadcrumb">
			<li><a href="<?php echo URL::site('manager'); ?>">Manager</a></li>
			<li><a href="<?php echo URL::site('manager/page'); ?>">Páginas</a></li>
			<li><?php echo $page->name; ?></li>
		</ol>

    <div class="btn-group" role="group">
		  <div class="btn-group">
		    <button type="button" class="btn btn-success" id="nav-btn-save">
		    	<span class="glyphicon glyphicon-floppy-disk"></span> Salvar
		    </button>
		  </div>
		  <!--
		  <div class="btn-group">
		    <button type="button" class="btn btn-default">Middle</button>
		  </div>
		  <div class="btn-group">
		    <button type="button" class="btn btn-default">Right</button>
		  </div>
		  -->
		</div>

		<div class="btn-group" role="group">
		  <div class="btn-group">
		    <button type="button" class="btn btn-default" id="nav-btn-add-block">
		    	<span class="glyphicon glyphicon-plus"></span> Bloco
		    </button>
		  </div>
		</div>

		<div class="btn-group" role="group">
			<div class="btn-group">
				<button type="button" class="disabled btn btn-default nav-btn-resolution" data-rotate="true">
					<span class="glyphicon glyphicon-repeat"></span> Rotate
				</button>
				<button type="button" class="btn btn-default nav-btn-resolution is_desktop active">Desktop</button>
				<button type="button" class="btn btn-default nav-btn-resolution" data-width="1024" data-height="768">Tablet</button>
				<button type="button" class="btn btn-default nav-btn-resolution" data-width="320" data-height="568">Mobile</button>
			</div>
		</div>
		
  </div>
</nav>

<iframe border="0" id="iframe-page" height="575" width="100%" src="<?php echo $page->link_preview(); ?>"></iframe>


<style>
	#iframe-page {
		margin-top: 10px;
	}
	#buttons-navbar .container-fluid {
		margin: 10px auto;
	}
</style>

<script>

	var iframe_width, iframe_height = null;

	var iframe_contents = document.getElementById('iframe-page').contentWindow;

	var navbar = $('body > .navbar:first');

	var buttons_navbar = $('#buttons-navbar');

	var iframe_page = $("#iframe-page");

	var nav_btn_save = $('#nav-btn-save');

	navbar.after(buttons_navbar);

	function iframe_page_refresh() {
		var height = iframe_height || $("#iframe-page").contents().find("body").height();
		if (height < 600) {
			height = 600;
		}
		iframe_page.height(height);
		iframe_width = iframe_width || '100%';
		iframe_page.width(iframe_width);
		iframe_page.css('margin-top', buttons_navbar.height() + 20);

		if (iframe_contents.edited != undefined && iframe_contents.edited) {
			nav_btn_save.removeClass('disabled');
		} else {
			nav_btn_save.addClass('disabled');
		}
	}

	$("#iframe-page").load(iframe_page_refresh);

	$(window).resize(iframe_page_refresh);
	
	setInterval(iframe_page_refresh, 1000);

	nav_btn_save.click(function(e) {
		e.preventDefault();
		iframe_contents.save_page().then(function(r) {
			alert('Salvo!');
		});
	});
	
	$('#nav-btn-add-block').click(function(e) {
		e.preventDefault();
		iframe_contents._block_add_form.dialog('open');
	});

	var current_resoluton = $('.nav-btn-resolution.active:first');
	var btn_rotate = $('.nav-btn-resolution[data-rotate]:first');
	$('.nav-btn-resolution').click(function(e) {
		e.preventDefault();
		var that = $(this);

		if (current_resoluton == that) {
			return;
		}
		current_resoluton.removeClass('active');
		that.addClass('active');

		var that_width = that.data('width');
		var that_height = that.data('height');
		var is_desktop = that.is('.is_desktop');
		
		if ( ! is_desktop) {
			btn_rotate.removeClass('disabled');
		} else {
			btn_rotate.addClass('disabled');
		}

		if (that.data('rotate')) {
			var width = iframe_width;
			iframe_width = iframe_height;
			iframe_height = width;
		} else {
			iframe_width = that_width;
			iframe_height = that_height;
		}
		
		current_resoluton = that;

		iframe_page_refresh();
	})

</script>