
CREATE TABLE `#!-PRÄFIX-!#anwaerter` (
  `ID` int(10) NOT NULL auto_increment,
  `user` varchar(20) collate latin1_general_ci NOT NULL,
  `pass` varchar(20) collate latin1_general_ci NOT NULL,
  `name` varchar(80) collate latin1_general_ci NOT NULL,
  `nachname` varchar(20) collate latin1_general_ci NOT NULL,
  `email` varchar(80) collate latin1_general_ci NOT NULL,
  `datum` varchar(20) collate latin1_general_ci NOT NULL,
  `text` varchar(500) collate latin1_general_ci NOT NULL,
  `tel` varchar(20) collate latin1_general_ci NOT NULL,
  `wohnort` varchar(200) collate latin1_general_ci NOT NULL,
  `geschlecht` varchar(20) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
