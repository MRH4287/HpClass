CREATE TABLE IF NOT EXISTS `#!-PR�FIX-!#config` (
  `ID` int(100) NOT NULL auto_increment,
  `name` varchar(100) collate latin1_general_ci NOT NULL,
  `ok` varchar(100) collate latin1_general_ci NOT NULL,
  `description` varchar(100) collate latin1_general_ci NOT NULL,
  `typ` varchar(10) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ;
