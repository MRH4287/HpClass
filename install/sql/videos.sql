CREATE TABLE `#!-PRÄFIX-!#videos` (
  `ID` int(10) NOT NULL auto_increment,
  `user` varchar(200) collate latin1_general_ci NOT NULL,
  `Titel` varchar(20) collate latin1_general_ci NOT NULL,
  `HTML` text collate latin1_general_ci NOT NULL,
  `datum` varchar(20) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
