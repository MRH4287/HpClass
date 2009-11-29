CREATE TABLE IF NOT EXISTS `#!-PRÄFIX-!#posts` (
  `ID` int(100) NOT NULL AUTO_INCREMENT,
  `threadid` int(100) NOT NULL,
  `userid` int(100) NOT NULL,
  `text` text COLLATE latin1_general_ci NOT NULL,
  `timestamp` int(255) NOT NULL,
  `lastedit` int(100) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci  ;
