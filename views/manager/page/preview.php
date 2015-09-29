
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
				<button type="button" class="btn btn-default nav-btn-resolution" data-rotate="true">
					<span class="glyphicon glyphicon-repeat"></span> Rotare
				</button>
				<button type="button" class="btn btn-default nav-btn-resolution">Desktop</button>
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

	navbar.after(buttons_navbar);

	function iframe_page_refresh() {
		var height = iframe_height || $("#iframe-page").contents().find("body").height();
		iframe_page.height(height);
		iframe_width = iframe_width || '100%';
		iframe_page.width(iframe_width);
		iframe_page.css('margin-top', buttons_navbar.height() + 20);
	}

	$("#iframe-page").load(iframe_page_refresh);

	$(window).resize(iframe_page_refresh);
	
	setInterval(iframe_page_refresh, 1000);

	$('#nav-btn-save').click(function(e) {
		e.preventDefault();
		iframe_contents.save_page().success(function(r) {
			alert('Salvo!');
		});
	});
	
	$('#nav-btn-add-block').click(function(e) {
		e.preventDefault();
		iframe_contents._block_add_form.dialog('open');
	});

	$('.nav-btn-resolution').click(function(e) {
		e.preventDefault();
		var that = $(this);
		if (that.data('rotate')) {
			var width = iframe_width;
			iframe_width = iframe_height;
			iframe_height = width;
		} else {
			iframe_width = that.data('width');
			iframe_height = that.data('height');
		}
		iframe_page_refresh();
	})

</script>