CREATE TABLE `#!-PRÄFIX-!#modul` (
  `ID` int(100) NOT NULL auto_increment,
  `Name` varchar(100) collate latin1_general_ci NOT NULL,
  `run` varchar(100) collate latin1_general_ci NOT NULL default 'autorun.php',
  `description` text collate latin1_general_ci NOT NULL,
  `date` varchar(100) collate latin1_general_ci NOT NULL,
  `path` varchar(100) collate latin1_general_ci NOT NULL,
  `active` int(1) NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=3 ;
