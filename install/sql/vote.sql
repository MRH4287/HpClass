CREATE TABLE IF NOT EXISTS `#!-PR�FIX-!#vote` (
  `ID` int(100) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `userid` int(100) NOT NULL,
  `antworten` text NOT NULL,
  `ergebnisse` text NOT NULL,
  `timestamp` int(255) NOT NULL,
  `upto` int(255) NOT NULL,
  `voted` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1  ;