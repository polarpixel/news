<?php
$mypage = 'news';

/********** Settings **********/
$CAST = array (
      'news_type'	=> 'string',
      'teaser'		=> 'string',
      'news_cats'	=> 'string'
);

/********** UPDATE/SAVE SETTINGS **********/
if ($func == 'update')
{

  // GET ADDON SETTINGS FROM REQUEST
  $myCONF = rex_batch_cast($_POST,$CAST);

  // UPDATE REX
  $REX['ADDON'][$mypage]['settings'] = $myCONF;

  // SAVE ADDON SETTINGS
  $DYN    = '$REX["ADDON"]["'.$mypage.'"]["settings"] = '.stripslashes(var_export($myCONF,true)).';';
  $config = $REX['INCLUDE_PATH'].'/addons/'.$mypage.'/config.inc.php';
  rex_replace_dynamic_contents($config, $DYN);
  echo rex_info('Einstellungen wurden gespeichert.');
}

/********** Kategorien Selectbox **********/
$cats_select = new rex_select();
$cats_select->setSize(1);
$cats_select->setName('news_cats');
$cats_select->addOption('ja','true');
$cats_select->addOption('nein','false');
$cats_select->setAttribute('style','width:70px;margin-left:20px;');
$cats_select->setSelected($REX['ADDON'][$mypage]['settings']['news_cats']);

/********** Teaser Selectbox **********/
$teaser_select = new rex_select();
$teaser_select->setSize(1);
$teaser_select->setName('teaser');
$teaser_select->addOption('ja','true');
$teaser_select->addOption('nein','false');
$teaser_select->setAttribute('style','width:70px;margin-left:20px;');
$teaser_select->setSelected($REX['ADDON'][$mypage]['settings']['teaser']);

/********** Archiv Selectbox **********/
$news_type_select = new rex_select();
$news_type_select->setSize(1);
$news_type_select->setName('news_type');
$news_type_select->addOption('Datum','date');
$news_type_select->addOption('Menge','limit');
$news_type_select->setAttribute('style','width:70px;margin-left:20px;');
$news_type_select->setSelected($REX['ADDON'][$mypage]['settings']['news_type']);


echo '
<div class="rex-addon-output">
<div class="rex-form">

	<form action="index.php" method="post">
	<input type="hidden" name="page" value="news" />
	<input type="hidden" name="subpage" value="settings" />
	<input type="hidden" name="func" value="update" />';

	echo '
	<fieldset class="rex-form-col-1">
	<legend>Einstellungen</legend>
		<div class="rex-form-wrapper">

			<div class="rex-form-row">
				<p class="rex-form-col-a rex-form-select">
					<label for="news_cats" style="width: 100px;">mit Kategorien</label> 
					'.$cats_select->get().'
				</p>
				<p style="margin-left: 130px; width: 600px; margin-top: 10px; margin-bottom: 20px;">Falls ja, dann steht ein weiterer Menüpunkt "Kategorien" zur Verfügung. Diese Kategorien können den Meldungen zugewiesen werden und dienen so als Tags. Auch eine Tagcloud kann damit realisiert werden.</p>
			</div><!-- /rex-form-row -->

			<div class="rex-form-row">
				<p class="rex-form-col-a rex-form-select">
					<label for="teaser" style="width: 100px;">mit Teaserfeld</label> 
					'.$teaser_select->get().'
				</p>
				<p style="margin-left: 130px; width: 600px; margin-top: 10px; margin-bottom: 20px;">Falls ja, dann existiert im Formular des Newsmeldungen ein Teaser-Feld. Falls nein gewählt wird, wird der Newstext als Teaser verwendet.</p>
			</div><!-- /rex-form-row -->

			<div class="rex-form-row">
				<p class="rex-form-col-a rex-form-select">
					<label for="news_type" style="width: 100px;">Archiv nach</label> 
					'.$news_type_select->get().'
				</p>
				<p style="margin-left: 130px; width: 600px; margin-top: 10px; margin-bottom: 20px;">Regelt, ob die aktuellen Meldungen und das Archiv zeit- oder mengengesteuert funktionieren.</p>
			</div><!-- /rex-form-row -->

		<div class="rex-form-row rex-form-element-v2">
			<p class="rex-form-submit">
				<input class="rex-form-submit" type="submit" id="sendit" name="sendit" value="Einstellungen speichern" />
			</p>
		</div><!-- /rex-form-row -->

	</div><!-- /rex-form-wrapper -->
	</form>

</div><!-- /rex-form -->
</div><!-- /rex-addon-output -->';

unset($news_type_select, $teaser, $cats_select);
?>