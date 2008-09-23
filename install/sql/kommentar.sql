CREATE TABLE `#!-PRÄFIX-!#kommentar` (
  `ID` int(10) NOT NULL auto_increment,
  `zuid` int(10) NOT NULL,
  `autor` varchar(20) collate latin1_general_ci NOT NULL,
  `datum` varchar(80) collate latin1_general_ci NOT NULL,
  `text` varchar(500) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
