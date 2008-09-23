CREATE TABLE `#!-PRÄFIX-!#pm` (
  `ID` int(20) NOT NULL auto_increment,
  `von` varchar(80) collate latin1_general_ci NOT NULL,
  `zu` varchar(80) collate latin1_general_ci NOT NULL,
  `Datum` varchar(20) collate latin1_general_ci NOT NULL,
  `Text` text collate latin1_general_ci NOT NULL,
  `Betreff` text collate latin1_general_ci NOT NULL,
  `gelesen` char(1) collate latin1_general_ci NOT NULL,
  `timestamp` varchar(200) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `timestamp` (`timestamp`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

