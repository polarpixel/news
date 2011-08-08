<script type="text/javascript">
	/* <![CDATA[ */ 
	jQuery().ready(function(){
		jQuery("#bsdiv").hide();
		jQuery("#bslink").click(function() {
			jQuery("#bsdiv").slideToggle();
		});
	});
	/* ]]> */ 
</script>


<?php
$id = rex_request('id', 'int');
$toggle = rex_request('toggle', 'int');

// Eintragsliste
if ($func == '') {

	// STATUS ON-OFFLINE WECHSELN
	if (isset($toggle) and $toggle != '') {
    	$toggle_status = new rex_sql;
//		$toggle_status->debugsql = true;
    	$toggle_status->setQuery("UPDATE ".$REX['TABLE_PREFIX']."news_meldungen set status = '".$_GET[toggle]."' WHERE id = '".$id."' LIMIT 1");
	}
    $message = 'Status geändert!';

    	
    $list = new rex_list('SELECT id, datum, DATE_FORMAT(datum,"%d. %m. %Y") AS date, titel, status FROM '.$REX['TABLE_PREFIX'].'news_meldungen order by datum', 200);
	
	$imgHeader = '<a href="'. $list->getUrl(array('func' => 'add')) .'"><img src="media/metainfo_plus.gif" alt="add" title="add" /></a>';
	
	$list->setColumnSortable('date');
	$list->setColumnSortable('titel');

	$list->addColumn(
		$imgHeader, 
		'<img src="media/metainfo.gif" alt="field" title="field" />', 
		0, 
		array(
			'<th class="rex-icon">###VALUE###</th>',
			'<td class="rex-icon">###VALUE###</td>'
		)
	);

	$list->setColumnParams (
		$imgHeader, 
		array('func' => 'edit', 'id' => '###id###')
	);
	
	$list->removeColumn('id');
	$list->removeColumn('datum');
	
	$list->setColumnLabel('date', $I18N_news->msg('date'));
	$list->setColumnLabel('titel', $I18N_news->msg('title'));	

	$list->setColumnLabel('status', $I18N_news->msg('on_off'));
 	$list->setColumnParams('status', array('id' => '###id###'));
 	$list->setColumnFormat('status', 'custom',
    create_function(
    	'$params',
    	'$list = $params["list"];
    	if($list->getValue("status") == "1")
    		$params = array("toggle" => "0");
    	else
    		$params = array("toggle" => "1");
    	return $list->getColumnLink("status", $list->getValue("status") != "1" ? 
    	"<span style=\'color: red;\'>Offline</span>" : 
    	"<span style=\'color: green;\'>Online</span>",
    	$params);'
    		
    )
  );
	
	$list->setColumnLayout (
		'id',
		array(
			'<th class="rex-icon">###VALUE###</th>',
			'<td class="rex-icon">###VALUE###</td>'
		)
	);
	
	$list->setColumnParams('name', array('func' => 'edit', 'id' => '###id###'));
	$list->setColumnParams('date', array('func' => 'edit', 'id' => '###id###'));
	$list->setColumnParams('titel', array('func' => 'edit', 'id' => '###id###'));

	$list->show();
}

// Formular

elseif ($func == 'edit' || $func == 'add') {

	$form = new rex_form($REX['TABLE_PREFIX'].'news_meldungen',"Newsmeldungen","id=".$id,"post",false);

    $field = &$form->addTextAreaField('text');
    $field->setLabel("Description");
	$field->setAttribute('style','height: 100px;');
	$field->setHeader('
	<div class="rex-form-row" style="padding: 10px 5px;"><a href="javascript:void(0);" id="bslink">Description, Keywords ...</a></div>
	<div id="bsdiv"">');
	$field->setFooter('
	</div>');

	$field = &$form->addTextField('titel');
    $field->setLabel("Titel");
    
    $field = &$form->addTextField('datum');
    $field->setLabel("Datum");
	$field->setAttribute('style','width: 100px');
	$field->setAttribute('id','datepicker');
    
	if ($REX["ADDON"]["news"]["settings"]['teaser'] == 'true') {
	    $field = &$form->addTextAreaField('text');
    	$field->setLabel("Teaser-Text");
		$field->setAttribute('style','height: 100px;');
    }
    
    $field = &$form->addTextAreaField('full_text');
    $field->setLabel("Voller Text");
    $field->setLabel("");
	$field->setAttribute('style','width: 450px; height: 300px');
	$field->setAttribute('class','markitup-text');
	$field->setHeader('<table class="addon"><tr><td style="width: 150px; padding-left: 5px; padding-top: 10px;vertical-align: top;">Voller Text</td><td>'.
	a287_markitup::markitup('textarea.markitup-text',
	'bold,italic,separator,listbullet,listnumeric,,separator,linkintern,linkextern',
		'472','380'
	));
    $field->setFooter('</td></tr></table>');

    $field = &$form->addMediaField('pic');
    $field->setLabel("Bild");

	$field = &$form->addSelectField('status');
    $field->setLabel("Status");
	$select = &$field->getSelect();
	$select->setSize(1);
	$select->addOption('Online',1);
	$select->addOption('Offline',0);
	$select->setAttribute('style','width: 100px');
	
	// Standardwert: 1
	if ($field->getValue()== "") {
		$field->setValue(1);
	}
	
	if ($REX['ADDON']['news_kat'] == 'j') {

		// Selectfeld für Kategorien
		$field = &$form->addSelectField('r_kat');
		$field->setLabel("Kategorie");
		$select =& $field->getSelect();
		$select->setSize(5);
		$field->setAttribute('multiple', 'multiple');
	    $query = 'SELECT kat as label, id FROM '.$REX['TABLE_PREFIX'].'news_kats';
	   	$select->addSqlOptions($query);

	}

	if($func == 'edit') {
		$form->addParam('id', $id);
    }

	$form->show();
}
?>