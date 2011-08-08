DROP TABLE IF EXISTS `%TABLE_PREFIX%news_kats`;
DROP TABLE IF EXISTS `%TABLE_PREFIX%news_meldungen`;

CREATE TABLE IF NOT EXISTS `%TABLE_PREFIX%news_kats` (
  `id` int(11) NOT NULL auto_increment,
  `kat` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `%TABLE_PREFIX%news_meldungen` (
  `id` int(11) NOT NULL auto_increment,
  `datum` date NOT NULL default '0000-00-00',
  `titel` varchar(255) NOT NULL default '',
  `pic` varchar(255) NOT NULL default '',
  `text` text NOT NULL,
  `full_text` text NOT NULL,
  `status` char(1) NOT NULL default '',
  `r_kat` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;
