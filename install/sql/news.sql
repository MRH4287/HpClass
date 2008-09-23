CREATE TABLE `#!-PRÄFIX-!#news` (
  `ID` int(10) NOT NULL auto_increment,
  `ersteller` varchar(20) collate latin1_general_ci NOT NULL,
  `datum` varchar(20) collate latin1_general_ci NOT NULL,
  `titel` varchar(80) collate latin1_general_ci NOT NULL,
  `typ` varchar(20) collate latin1_general_ci NOT NULL,
  `level` varchar(20) collate latin1_general_ci NOT NULL,
  `text` varchar(9999) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
