
<nav id="buttons-navbar" class="navbar navbar-default navbar-fixed" data-spy="affix">
  <div class="container-fluid">
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
	var iframe_contents = document.getElementById('iframe-page').contentWindow;

	var navbar = $('body > .navbar:first');

	var buttons_navbar = $('#buttons-navbar');

	navbar.after(buttons_navbar);

	function iframe_page_refresh() {
		$("#iframe-page").height($("#iframe-page").contents().find("body").height());
	}

	$("#iframe-page").load(iframe_page_refresh);

	$(window).resize(iframe_page_refresh);
	
	setInterval(iframe_page_refresh, 1000);

	$('#nav-btn-save').click(function(e) {
		e.preventDefault();
		iframe_contents.save_page().done(function(r) {
			if (r) {
				alert('Salvo!');
			}
		});
	});
</script>