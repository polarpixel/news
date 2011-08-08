<?php
$id = rex_request('id', 'int');

// Eintragsliste

if ($func == '') {
	$list = new rex_list('SELECT * from '.$REX['TABLE_PREFIX'].'news_kats order by kat');
	
	$imgHeader = '<a href="'. $list->getUrl(array('func' => 'add')) .'"><img src="media/metainfo_plus.gif" alt="add" title="add" /></a>';

	$list->addColumn(
		$imgHeader, 
		'<img src="media/metainfo.gif" alt="field" title="field" />', 
		0, 
		array(
			'<th class="rex-icon">###VALUE###</th>',
			'<td class="rex-icon">###VALUE###</td>'
		)
	);					
	
	
	$list->setColumnLabel('id', "ID");	
	$list->setColumnLabel('kat', 'Kategorie');
	
	$list->setColumnParams('id', array('func' => 'edit', 'id' => '###id###'));
	$list->setColumnParams('kat', array('func' => 'edit', 'id' => '###id###'));
	$list->show();
}

// Formular

elseif ($func == 'edit' || $func == 'add') {
	$form = new rex_form($REX['TABLE_PREFIX'].'news_kats',"Kategorien","id=".$id,"post",false);
	
	$field = &$form->addTextField('kat');
    $field->setLabel("Kategorie");
   
	if($func == 'edit') {
		$form->addParam('id', $id);
	}

	$form->show();
}
?>