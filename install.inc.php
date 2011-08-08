<?php
if (OOAddon::isAvailable('textile') != 1 || OOAddon::isAvailable('markitup') != 1) {
	// Installation nicht erfolgreich
	$REX['ADDON']['install']['news'] = 0;
	$REX['ADDON']['installmsg']['news'] = 'AddOn "Textile" und/oder "Markitup" ist nicht installiert und aktiviert.';

} else {

	$REX['ADDON']['install']['news'] = 1;
	// 1 = installiert, 0 nicht installiert

}
?>
