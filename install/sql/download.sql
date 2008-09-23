CREATE TABLE `#!-PRÄFIX-!#download` (
  `ID` int(10) NOT NULL auto_increment,
  `dateityp` varchar(100) collate latin1_general_ci NOT NULL,
  `datei` longblob,
  `titel` varchar(20) collate latin1_general_ci NOT NULL,
  `datum` varchar(80) collate latin1_general_ci NOT NULL,
  `autor` varchar(20) collate latin1_general_ci NOT NULL,
  `level` varchar(20) collate latin1_general_ci NOT NULL,
  `dateiname` varchar(200) collate latin1_general_ci NOT NULL,
  `Zeitstempel` datetime NOT NULL,
  `beschreibung` varchar(500) collate latin1_general_ci NOT NULL,
  `kat` INT(10) NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
