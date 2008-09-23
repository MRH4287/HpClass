CREATE TABLE `#!-PRÄFIX-!#right` (
  `ID` int(10) NOT NULL auto_increment,
  `level` int(2) NOT NULL,
  `right` varchar(120) collate latin1_general_ci NOT NULL,
  `ok` varchar(100) collate latin1_general_ci NOT NULL,
  `description` varchar(200) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
