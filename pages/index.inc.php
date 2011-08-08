<?php
$Basedir = dirname(__FILE__);
$page = rex_request('page', 'string');
$subpage = rex_request('subpage', 'string');
$func = rex_request('func', 'string');
$mypage = 'news';

include $REX['INCLUDE_PATH'].'/layout/top.php';

$arr_kat = '';
	if ($REX["ADDON"]["news"]["settings"]['news_cats'] == 'true') {
		$arr_kat = array('kats',"Kategorien");
	}
$subpages = array(
	array('meldungen',"Meldungen"),
	$arr_kat,
	array('settings',"Einstellungen")
);

rex_title("News", $subpages);


switch($subpage) {

	case "kats":
			require $Basedir .'/cats.inc.php';
			break;

	case "settings":
			require $Basedir .'/settings.inc.php';
			break;

	default:
		require $Basedir .'/messages.inc.php';

}

include $REX['INCLUDE_PATH'].'/layout/bottom.php';
?>