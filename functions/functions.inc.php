<?php
rex_register_extension('PAGE_HEADER', 'AddJS');

function AddJS($params) {
	echo '
  	<script type="text/javascript" src="/files/addons/news/ui.datepicker.js"></script>

	<link rel="stylesheet" type="text/css" href="/files/addons/news/ui.css" media="screen, projection, print" />

	<script type="text/javascript">
	jQuery(function() {
		jQuery("#datepicker").datepicker({
			numberOfMonths: 2,
			showButtonPanel: true
		});
		jQuery("#datepicker").datepicker(\'option\', {dateFormat: \'yy-mm-dd\' });
	});
	</script>';
}
?>