CREATE TABLE IF NOT EXISTS `#!-PRÄFIX-!#forums` (
  `ID` int(100) NOT NULL AUTO_INCREMENT,
  `titel` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `userid` int(100) NOT NULL,
  `timestamp` int(255) NOT NULL,
  `level` int(100) NOT NULL,
  `passwort` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `visible` int(5) NOT NULL,
  `description` varchar(150) COLLATE latin1_general_ci NOT NULL,
  `type` varchar(100) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=5 ;
