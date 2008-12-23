CREATE TABLE `#!-PR�FIX-!#user` (
  `ID` int(10) NOT NULL auto_increment,
  `user` varchar(20) character set latin1 collate latin1_general_ci NOT NULL,
  `pass` varchar(20) character set latin1 collate latin1_general_ci NOT NULL,
  `name` varchar(20) character set latin1 collate latin1_general_ci default NULL,
  `nachname` varchar(20) character set latin1 collate latin1_general_ci default NULL,
  `email` varchar(30) character set latin1 collate latin1_general_ci default NULL,
  `alter` varchar(20) character set latin1 collate latin1_general_ci default NULL,
  `geburtstag` varchar(20) character set latin1 collate latin1_general_ci default NULL,
  `wohnort` varchar(20) character set latin1 collate latin1_general_ci default NULL,
  `datum` varchar(20) character set latin1 collate latin1_general_ci NOT NULL,
  `level` varchar(20) character set latin1 collate latin1_general_ci NOT NULL,
  `lastlogin` int(80) NOT NULL,
  `geschlecht` varchar(20) character set latin1 collate latin1_general_ci default NULL,
  `clan` varchar(200) character set latin1 collate latin1_general_ci default NULL,
  `clanhistory` text character set latin1 collate latin1_general_ci,
  `clanhomepage` varchar(200) character set latin1 collate latin1_general_ci default NULL,
  `clantag` varchar(20) character set latin1 collate latin1_general_ci default NULL,
  `cpu` varchar(200) character set latin1 collate latin1_general_ci default NULL,
  `ram` varchar(200) character set latin1 collate latin1_general_ci default NULL,
  `graka` varchar(200) character set latin1 collate latin1_general_ci default NULL,
  `hdd` varchar(200) character set latin1 collate latin1_general_ci default NULL,
  `tel` varchar(20) character set latin1 collate latin1_general_ci NOT NULL,
  `bild` longblob NOT NULL,
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `user` (`user`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
