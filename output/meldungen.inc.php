<?php
$nid = rex_request('nid', 'int');
if ($nid == 0) {
// Newsliste

	$seite = rex_request('page', 'int');
	
	// Einstellungen zum Blättern
	$max_show = 2; // Meldungen x pro Seite
	$max_page = 15; //maximale Seitenzahl
	
	$startwert = 0; //beginnt bei Datensatz x
	$stopwert = $max_show * $max_page;
	
	$blaettern = '';
	$limit_total = " LIMIT ".$startwert.",".$stopwert;
	
	$en_date = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'Oktober', 'November', 'December');
	$de_date = array('Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'December');
	
	$add_query1 = '';
	$add_query2 = '';
	if ($REX['ADDON']['news_kat'] == true) {
		if (isset($_GET[kid])) {
			$kid = rex_request('kid', 'int');
		    $add_query1 = ', '.$REX['TABLE_PREFIX'].'news_kats';
		    $add_query2 = ' AND r_kat LIKE "%|'.$kid.'|%"
		    				AND '.$REX['TABLE_PREFIX'].'news_kats.id = "'.$kid.'"';
		}
	}
	
	$sql = rex_sql::factory();
	// $sql->debugsql = true;
	$sql_query = '
		SELECT
			*,
			DATE_FORMAT (datum, "%e. %M %Y") AS date
		FROM
			'.$REX['TABLE_PREFIX'].'news_meldungen
			 '.$add_query1.'
		WHERE
			datum <= CURDATE()
			 '.$add_query2.'
		ORDER BY
			datum';
	
	$sql->setQuery($sql_query.$limit_total);
	$anz = $sql->rows;
	
	if ($anz > 0) { // Prüfung auf vorhandene Datensätze
	
		$pages_total = ceil($anz/$max_show);
		
		// Blättern Beginn
		if($seite == 0 || $seite < 1) {
			$seite = 1;
		} elseif($seite > 1 && $pages_total < $seite ) {
			$seite = $pages_total;
		}
		
		$start	= ($seite * $max_show) + $startwert - $max_show;
		$stop	= $max_show;
		$limit	= " LIMIT ".$start.",".$stop;
		
		for ( $k=1; $k <= $pages_total; $k++) {
			if ( $k == $seite ) {	
				$blaettern .= '<li class="active"><a href="'.rex_getUrl($this->article_id, $this->clang, array("page"=>$k)).'">'.$k.'</a></li>';
			} elseif ( $k == $pages_total ){
		
				$blaettern .= '
				<li><a href="'.rex_getUrl($this->article_id, $this->clang, array("page"=>$k)).'">'.$k.'</a></li>';
		
			} else {
		
				$blaettern .= '
				<li><a href="'.rex_getUrl($this->article_id, $this->clang, array("page"=>$k)).'">'.$k.'</a></li>';
		
			}
		}
		
		if ( $seite == $pages_total ) {
			$bis = $anz;
		} else {
			$bis = $start + $max_show;
		}
		// Blättern Ende
		
		$sql->setQuery($sql_query.$limit);
		
		// Kategorien / Tags
		if ($REX['ADDON']['news_kat'] == true) {
			
			$sql_kat = rex_sql::factory();
			// $sql_kat->debugsql = true;
			$query_kat = '
			SELECT
				*
			FROM
				'.$REX['TABLE_PREFIX'].'news_kats
			ORDER BY
				kat';
			$sql_kat->setQuery($query_kat);
			
			// Tags-Array
			$kats = array();
			for ($j=0; $j<$sql_kat->getRows(); $j++) {
				$kats[$j]['id']			=	$sql_kat->getValue('id');
				$kats[$j]['katname']	=	$sql_kat->getValue('kat');
				$sql_kat->next();
			}
		}
		
		if (isset($kid)) {
			echo '<h6>Gewählte Kategorie: '.$sql->getValue($REX['TABLE_PREFIX'].'news_kats.kat').'</h6>
			<div class="separator"></div>';
		}
	
		for ($i=0; $i<$sql->getRows(); $i++) {
		
			$newstext = '';
			if($sql->getValue('text') != '') {
				$newstext = $sql->getValue('text');
				$newstext = htmlspecialchars_decode($newstext);
				$newstext = str_replace("<br />", "", $newstext);
				$newstext = rex_a79_textile($newstext);
			}
		
			$date_new = str_replace($en_date,$de_date,$sql->getValue('date'));
		
			$kat_list = '';
			if ($REX['ADDON']['news_kat'] == true) {
				// Tags verlinken
				$selected_kats = explode ("|", $sql->getValue('r_kat'));
			
				foreach ($kats as $kat=>$details) {
					if (in_array($details['id'], $selected_kats)) {
						$kat_list .= '<li class="post_tags"><a href="'.rex_getUrl($this->article_id,$this->clang,array('kid'=>$details['id'])).'">'.$details['katname'].'</a></li>';
					}
				}
				// Tags Ende
			}
		
			echo '
			<h1><a href="'.rex_getUrl($this->article_id, $this->clang, array ( 'nid' => $sql->getValue('id')) ).'">'.$sql->getValue('titel').'</a></h1> 
			<ul class="post_info"> 
				<li class="post_date">'.$date_new.'</li>
				'.$kat_list.'
			</ul> 
			<div class="img_post_container"> 
				<a href="'.rex_getUrl($this->article_id, $this->clang, array ( 'nid' => $sql->getValue('id')) ).'"><img src="index.php?rex_img_type=news&amp;rex_img_file='.$sql->getValue('pic').'" alt="" /></a> 
			</div> 
			<div class="content">
			'.$newstext.'
			</div>
			<p><a href="'.rex_getUrl($this->article_id, $this->clang, array ( 'nid' => $sql->getValue('id')) ).'">Mehr lesen ...</a></p> 
			<div class="clear"></div>';
			
			$sql->next();
		}
		
		echo '
		<div class="grid4 first">';
		
		// Pagination anzeigen, wenn mehr als 1 Seite
		if ($pages_total > 1) {
			echo '	
		    <ol class="pagination"> 
				'.$blaettern.'
		    </ol>';
		}
		
			// Seiteninfo immer anzeigen
		    echo '
		    <p class="pages">Seite '.$seite.' von '.$pages_total.'</p> 
		</div> 
		<div class="clear"></div> ';

	} // Ende Prüfung auf vorhandene Datensätze

// Ende Newsliste
} else {

// News-Detailansicht

	$en_date = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'Oktober', 'November', 'December');
	$de_date = array('Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'December');
	
	// Kategorien / Tags
	if ($REX['ADDON']['news_kat'] == true) {
		
		$sql_kat = rex_sql::factory();
		// $sql_kat->debugsql = true;
		$query_kat = '
		SELECT
			*
		FROM
			'.$REX['TABLE_PREFIX'].'news_kats
		ORDER BY
			kat';
		$sql_kat->setQuery($query_kat);
		
		// Tags-Array
		$kats = array();
		for ($j=0; $j<$sql_kat->getRows(); $j++) {
			$kats[$j]['id']			=	$sql_kat->getValue('id');
			$kats[$j]['katname']	=	$sql_kat->getValue('kat');
			$sql_kat->next();
		}
	}


	$sql = rex_sql::factory();
	// $sql->debugsql = true;
	$sql_query = '
	SELECT
		*,
		DATE_FORMAT (datum, "%e. %M %Y") AS date
	FROM
		'.$REX['TABLE_PREFIX'].'news_meldungen
	WHERE
		id = "'.$nid.'"';

	$sql->setQuery($sql_query);

	$newstext = '';
	if($sql->getValue('text') != '') {
		$newstext = $sql->getValue('text');
		$newstext = htmlspecialchars_decode($newstext);
		$newstext = str_replace("<br />", "", $newstext);
		$newstext = rex_a79_textile($newstext);
	}

	// Voller Text
	$newstext_full = '';
	if($sql->getValue('full_text') != '') {
		$newstext_full = $sql->getValue('full_text');
		$newstext_full = htmlspecialchars_decode($newstext_full);
		$newstext_full = str_replace("<br />", "", $newstext_full);
		$newstext_full = rex_a79_textile($newstext_full);
	}
		
	$date_new = str_replace($en_date,$de_date,$sql->getValue('date'));

	$kat_list = '';
	if ($REX['ADDON']['news_kat'] == true) {
		// Tags verlinken
		$selected_kats = explode ("|", $sql->getValue('r_kat'));
	
		foreach ($kats as $kat=>$details) {
			if (in_array($details['id'], $selected_kats)) {
				$kat_list .= '<li class="post_tags"><a href="'.rex_getUrl($this->article_id,$this->clang,array('kid'=>$details['id'])).'">'.$details['katname'].'</a></li>';
			}
		}
		// Tags Ende
	}

	echo '
	<h1>'.$sql->getValue('titel').'</h1> 
	<ul class="post_info"> 
		<li class="post_date">'.$date_new.'</li>
		'.$kat_list.'
	</ul> 
	<div class="img_post_container"> 
		<img src="index.php?rex_img_type=news&amp;rex_img_file='.$sql->getValue('pic').'" alt="" /> 
	</div> 
	<div class="content">
	'.$newstext.'
	'.$newstext_full.'
	</div>
	<p><a href="javascript:history.back();">Zurück ...</a></p> 
	<div class="clear"></div>';
			
}
?>