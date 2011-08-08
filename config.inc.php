<?php
$mypage = 'news';
require_once($REX['INCLUDE_PATH'].'/addons/'.$mypage.'/functions/functions.inc.php');
require_once($REX['INCLUDE_PATH'].'/addons/'.$mypage.'/functions/functions_helpers.inc.php');


$REX['ADDON']['rxid'][$mypage] = 'news';
$REX['ADDON']['page'][$mypage] = $mypage;    
$REX['ADDON']['name'][$mypage] = 'News';
$REX['ADDON']['perm'][$mypage] = 'news[]';
$REX['PERM'][] = 'news[]';

$REX['ADDON']['version'][$mypage] = "0.5";
$REX['ADDON']['author'][$mypage] = "Peter Bickel";
$REX['ADDON']['dbpref'][$mypage]=$REX['TABLE_PREFIX'].$REX['ADDON']['rxid'][$mypage].'_';

$I18N_news = new i18n($REX['LANG'], $REX['INCLUDE_PATH'].'/addons/'.$mypage.'/lang/'); 

/******** User settings ********/

// --- DYN
$REX["ADDON"]["news"]["settings"] = array (
  'page' => 'news',
  'subpage' => 'settings',
  'func' => 'update',
  'news_cats' => 'false',
  'teaser' => 'true',
  'news_type' => 'date',
  'sendit' => 'Einstellungen speichern',
);
// --- /DYN
?>