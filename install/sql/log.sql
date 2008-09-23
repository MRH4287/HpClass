CREATE TABLE `#!-PRÄFIX-!#log` (
  `ID` int(200) NOT NULL auto_increment,
  `user` varchar(200) collate latin1_general_ci NOT NULL,
  `timestamp` varchar(200) collate latin1_general_ci NOT NULL,
  `Ereignis` text collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
